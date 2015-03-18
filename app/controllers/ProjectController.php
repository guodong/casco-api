<?php
use Illuminate\Support\Facades\Input;

class ProjectController extends BaseController
{
    
    // 项目创建
    public function store()
    {
        $project = new Project(Input::get());
        $project->save();
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
        set_time_limit(0);
        $name = uniqid() . '.doc';
        move_uploaded_file($_FILES["file"]["tmp_name"], public_path() . '/files/' . $name);
        if (Input::get('type') == 'tc') {
            $url = 'http://192.100.212.31:8080/files/' . $name;
            $u = 'http://192.100.212.33/WebService1.asmx/readtc?url=' . $url;
            $data = file_get_contents($u);
            $d = json_decode($data);
            if (! $d) {
                return 1;
            }
            foreach ($d as $v) {
                if (empty($v))
                    continue;
                $tc = new Tc();
                $tc->document_id = $_POST['document_id'];
                $tc->tag = $v->tag;
                $tc->description = $v->description;
                $tc->pre_condition = $v->pre_condition;
                $tc->version = Input::get('version');
                $tc->save();
                foreach ($v->steps as $step){
                    $step = new TcStep((array)$step);
                    $tc->steps()->save($step);
                }
                foreach ($v->source as $source){
                    $s = Rs::where('tag', '=', $source)->first();
                    if($s){
                        $tc->sources()->attach($s->id);
                    }
                }
            }
            $rt = Tc::where('document_id', '=', Input::get('document_id'))->get();
            return $rt;
        }
        $url = 'http://192.100.212.31:8080/files/' . $name;
        $u = 'http://192.100.212.33/WebService1.asmx/InputWord?url=' . $url;
        $data = file_get_contents($u);
        $d = json_decode($data);
        if (! $d) {
            return 1;
        }
        foreach ($d as $v) {
            if (empty($v))
                continue;
            $rs = new Rs();
            $rs->document_id = $_POST['document_id'];
            $rs->tag = $v->title;
            $rs->allocation = $v->Allocation;
            $rs->category = $v->Category;
            $rs->implement = $v->Implement;
            $rs->priority = $v->Priority;
            $rs->contribution = $v->Contribution;
            $rs->description = $v->description;
            $rs->source = json_encode($v->Source);
            $rs->version = Input::get('version');
            $rs->save();
        }
        $rt = Rs::where('document_id', '=', Input::get('document_id'))->get();
        return $rt;
    }
    
    // 项目列表
    public function index()
    {
        $projects = Project::all();
        $projects->each(function ($v) {
            $v->participants;
            $v->vatstrs;
        });
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
