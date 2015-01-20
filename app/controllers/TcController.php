<?php

class TcController extends Controller{

	public function show($id)
	{
		return Rs::find($id);
	}

	public function index()
	{
	    $tcs = Tc::where('document_id', '=', $_GET['document_id'])->get();
        foreach ($tcs as &$v) {
            $v->steps;
            $v->rss;
        }
        return $tcs;
	}
}
