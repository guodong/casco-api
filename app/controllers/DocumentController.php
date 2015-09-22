<?php
use Illuminate\Support\Facades\Input;

class DocumentController extends Controller
{

    private function calresult($rs)
    {
        $v = $rs;
        $result = 1;
        $v->vat;
        $v->vatstr;
        if (count($v->tcs()) == 0 && $v->vatstr_result == 0) {
            return 0;
        }
        foreach ($v->tcs() as $vv) {  
            if ($vv->result == 2) {
                $result = 2;
                break;
            } elseif ($vv->result == 0) {  
                $result = 0;
            }
        }
        if ($result == 1) {
            if ($v->vatstr_result == 2) {
                $result = 2;
            }
        }
        return $result;
    }

    public function index()
    {   
    	
       if(!Input::get('project_id')){return  DB::table('document')->join('project','document.project_id','=','project.id')->select('document.project_id as project_id','project.name as project_name','document.id as document_id','document.name as document_name','document.type as document_type')->get();}
        $docs =(Document::where('project_id', '=', Input::get('project_id')));
        if (Input::get('type')){
            $docs = $docs->where('type', '=', Input::get('type'));
        }
        if(Input::get('mode') == 'related'){
            $doc = Document::find(Input::get('document_id'));
            $docs = $doc->dests;
            foreach ($docs as $v){
                $v->versions;
            }
            return $docs;
        }
        return $docs->get();
        if (! empty($_GET['project_id'])) {
            $d = Document::where('project_id', '=', $_GET['project_id']);
            if (Input::get('type') == 'tc') {
                $d = $d->where('type', '=', 'tc');
            } else 
                if (Input::get('type') == 'rs') {
                    $d = $d->where('type', '=', 'rs');
                } else {
                    $d = $d->where('type', '<>', 'folder');
                }return;
            $data = $d->get(); 
            foreach ($data as $v) {
                if ($v->type == 'rs') {
                    $v->count = count($v->latest_version()->rss);
                    $v->num_passed = 0;
                    $v->num_failed = 0;
                    $v->num_untested = 0;
                    foreach ($v->latest_version()->rss as $rs) {
                        if ($this->calresult($rs) == 0)
                            $v->num_untested ++;
                        elseif ($this->calresult($rs) == 1) {
                            $v->num_passed ++;
                        } else {
                            $v->num_failed ++;
                        }
                    }
                } else {
                    $v->count = count($v->latest_version()->tcs);
                    $v->num_passed = 0;
                    $v->num_failed = 0;
                    $v->num_untested = 0;
                    foreach ($v->latest_version()->tcs as $tc) {
                        if ($tc->result == 0)
                            $v->num_untested ++;
                        elseif ($tc->result == 1) {
                            $v->num_passed ++;
                        } else {
                            $v->num_failed ++;
                        }
                    }
                }
            }
            return Response::json($data);
        }
        return Document::all();
    }
    
    public function show($id)
    {
        $document = Document::find($id);
        $document->versions;
        return $document;
    }

    public function store()
    {
        $document = new Document(Input::get());
        $document->save();
        return $document;
    }

    public function update($id)
    {}

    public function destroy($id)
    {
      $document=Document::find($id);
       //delete project->graph

       $project=$document->project;
       $graph=json_decode($project->graph);
       $data=[];
       if($graph){
         foreach($graph->cells  as $nodes)
         {  
          if($nodes->type=='basic.Rect'&&$nodes->id==$id)
          {
             continue;
         
          }elseif($nodes->type=='fsa.Arrow'&&($nodes->source->id==$id||$nodes->target->id==$id)){
                 continue;
           }else{
           $data[]=$nodes;
           
         }
      
         }//foreach
       
        }//if
     //udate  project graph
       
        $graph->cells=($data);
       // var_dump(json_encode($graph));       
        $project->graph=json_encode($graph);
        $project->save();


       
       foreach($document->versions  as  $vs){
           Version::destroy($vs->id);//删除versions

       }
	    foreach($document->tcs as $tcs){
                    $tcs->delete();}
		foreach($document->version->rss as $rss){
                    $rss->delete();}
		//删除对应的关系表
		$r = Relation::where('src', '=', $id);
		$r->delete();
		$r = Relation::where('dest', '=', $id);
		$r->delete();
		$document->destroy($id);
  
		return $document;

    }
    public function version()
    {
        $doc = Document::find(Input::get('document_id'));
        if ($doc->type = 'rs')
            $vs = Rs::distinct()->get(array(
                'version'
            ));
        else
            $vs = Tc::distince()->get(array(
                'version'
            ));
        $versions = [];
        foreach ($vs as $v) {
            if ($v->version == '') {
                continue;
            }
            $versions[] = $v;
        }
        return $versions;
    }
}
