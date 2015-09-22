<?php
use Illuminate\Support\Facades\Input;

class ProjectController extends BaseController
{
    
    // 项目创建
    public function store()
    {
        $project = new Project(Input::get());
        $project->save();
        //表单额外的数据的接收方式哦
        foreach (Input::get('participants') as $v) {
            $project->participants()->attach($v['id']);
        }
        foreach (Input::get('vatstrs') as $v) {
            $vatstr = new Vatstr($v);
            $project->vatstrs()->save($vatstr);
        }
        return $project;
    }

    public function docfile()
    {
        if(Input::get('isNew') == 1) $version = Version::create(array('name'=>Input::get('version_id'),'document_id'=>Input::get('document_id')));
        else $version = Version::find(Input::get('version_id'));
        set_time_limit(0);
        $name = uniqid() . '.doc';
        move_uploaded_file($_FILES["file"]["tmp_name"], public_path() . '/files/' . $name);
        $version->filename = $name;
        $version->save();        
        ////
        if (Input::get('type') == 'tc') {
            $url = 'http://192.100.212.31:8080/files/' . $name;
            $u = 'http://192.100.212.33/WebService1.asmx/readtc?url=' . $url.'&filename='.$name;
            $data = @file_get_contents($u);
            if(!$data) return array('success'=>false, 'msg'=>'parse error');
            $d = json_decode($data);
            if (! $d) {
                return array('success'=>false, 'msg'=>'parse error');
            }
            foreach ($d as $v) {
                if (empty($v))
                    continue;
                $tc = new Tc();
                //$tc->document_id = $_POST['document_id'];
                $tc->tag = $v->tag;
                $tc->description = $v->description;
                $tc->pre_condition = $v->pre_condition;
                $tc->version_id = $version->id;
                $tc->source_json = json_encode($v->source);
                $tc->save();
                foreach ($v->steps as $step){
                    $step = new TcStep((array)$step);
                    $tc->steps()->save($step);
                }
//                 foreach ($v->source as $source){
//                     $s = Rs::where('tag', '=', $source)->orderBy('created_at', 'desc')->first();
//                     if($s){
//                         $tc->sources()->attach($s->id, array('tag'=>$source));
//                     }
//                 }
            }
            $rt = Tc::where('version_id', '=', $version->id)->get();
            return array('success'=>true, 'msg'=>'parse error');
        }
        
        // RS 
        $modify=0;$delete=0;$add=0;$flag_add=true;
        $url = 'http://192.100.212.31:8080/files/' . $name;
        $u = 'http://192.100.212.33/WebService1.asmx/InputWord?url=' . $url.'&filename='.$name;
        $data = file_get_contents($u);
        $d = json_decode($data);
        if (! $d) {
            return 1;
        }
        foreach ($d as $v) {
            if (empty($v))
                continue;
            $rs = new Rs();
            
            //$rs->document_id = $_POST['document_id'];
            $rs->tag = $v->title;
            //这里来进行一下遍历吧
            $document_id=$_POST['document_id'];
            foreach($document->rss as $rss){
            if($rss->tag==$v->title){
              //接下来查看是否所有的内容都已经修改
             $flag_add=false;
             $flag=($rss->allocation==$v->Allocation&&$rss->category==$$v->Category&&$rss->implement==$v->Implement
             &&$rss->priority==$v->Priority&&$rss->contribution==$v->Contribution&&$rss->description==$v->description
             &&$rss->source_json==json_encode($v->Source)&&$rs->version_id = $version->id);
             if(!$flag)$modify++;
             break;//得到结果，break;
            }else{
            	
            	continue;
            }
            
            $flag_add?$add++:'';
            
            }//foreach
            	
          
            $rs->allocation = $v->Allocation;
            $rs->category = $v->Category;
            $rs->implement = $v->Implement;
            $rs->priority = $v->Priority;
            $rs->contribution = $v->Contribution;
            $rs->description = $v->description;
            $rs->source_json = json_encode($v->Source);
            $rs->version_id = $version->id;
            $rs->save();
//             $rs->sources()->detach();
//             foreach ($v->Source as $source){
//                 $s = Rs::where('tag', '=', $source)->orderBy('created_at', 'desc')->first();
//                 if($s){
//                     $rs->sources()->attach($s->id);
//                 }
//             }
        }
        $rt = Rs::where('version_id', '=', $version->id)->get();
        $ans='{"msg":[{"add":$add,"modify":$modify,"delete":$delete}]}';
        return  $ans;
    }
     
    // 项目列表
    public function index()
    {
        if (Input::get('user_id')) {
            $user = User::find(Input::get('user_id'));
            if ($user) {
                $projects = $user->projects;
            }else{
            	
            	$projects=null;
            }
        } else {
            $projects = Project::all();
        }
        if($projects){
        $projects->each(function ($v)
        {
            $v->participants;
            
        });
        }
        return $projects;
    }

    public function show($id)
    {
        $p = Project::find($id);
        // $p->graph = json_decode($p->graph);
        $p->documents;
        return $p;
    }

    public function update($id)
    {
        $project = Project::find($id);
        $data = Input::get();
        if (! empty($data['graph'])) {
            $graph = json_decode($data['graph']);
            foreach ($graph->cells as $node) {
                if ($node->type != 'fsa.Arrow')
                    continue;
                $src = Document::find($node->source->id);
                $dst = Document::find($node->target->id);
                $src->dests()->attach($dst);
            }
        }
        $project->update($data);
        if (Input::get('participants')) {
            $ids = array();
            foreach (Input::get('participants') as $v) {
                $ids[] = $v['id'];
            }
            $project->participants()->sync($ids);
        }
        
        // vatstr的修改处理可能有问题，当有的tc已经指派了vatstr，下面的操作会删掉全部vatstr重新添加，修改了vatstr的id，导致tc的vatstr索引野指针
        if (Input::get('vatstrs')) {
            $project->vatstrs()->delete();
            foreach (Input::get('vatstrs') as $v) {
                $vatstr = new Vatstr($v);
                $project->vatstrs()->save($vatstr);
            }
        }
        return $project;
    }
}
