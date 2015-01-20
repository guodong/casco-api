<?php

use Illuminate\Support\Facades\Input;
class DocumentController extends Controller{

    public function index()
    {
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

}
