<?php

use Illuminate\Support\Facades\Input;
class DocumentController extends Controller{

    public function index()
    {
        if (!empty($_GET['project_id'])){
            $d = Document::where('project_id', '=', $_GET['project_id'])->where('type','<>','folder')->get();
            return Response::json($d);
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

}
