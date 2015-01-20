<?php

class RsController extends Controller{

	public function show($id)
	{
		return Rs::find($id);
	}

	public function index()
	{
	    $rss = Rs::where('document_id','=',$_GET['document_id'])->get();
	    foreach ($rss as $v){
	        $v->tcs;
	    }
	    return $rss;
	}
}
