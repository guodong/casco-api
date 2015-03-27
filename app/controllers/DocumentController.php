<?php

use Illuminate\Support\Facades\Input;
class DocumentController extends Controller{

    public function index()
    {
        if (!empty($_GET['project_id'])){
            $d = Document::where('project_id', '=', $_GET['project_id'])->where('type','<>','folder');
            if (Input::get('type') == 'tc'){
                $d = $d->where('type', '=', 'tc');
            }
            return Response::json($d->get());
        }
        return Document::all();
    }
    
	public function show($id)
	{
		return Document::find($id);
	}
	
	public function store()
	{
	    $document = new Document(Input::get());
	    $document->save();
	    return $document;
	}
	
	public function update($id)
	{
	}

	public function version()
	{
	    $doc = Document::find(Input::get('document_id'));
	    if($doc->type = 'rs')
	        $vs = Rs::distinct()->get(array('version'));
	    else 
	        $vs = Tc::distince()->get(array('version'));
	    $versions = [];
	    foreach ($vs as $v){
	        if ($v->version == ''){
	            continue;
	        }
	        $versions[] = $v;
	    }
	    return $versions;
	}
}
