<?php

use Illuminate\Support\Facades\Input;
class TcController extends Controller{

	public function show($id)
	{
		return Tc::find($id);
	}

	public function index()
	{
	    $tcs = Tc::where('document_id', '=', $_GET['document_id'])->get();
        
        $tcs->each(function($tc){
            $tc->steps;
            $tc->sources;
        });
        return $tcs;
	}
	
	public function store()
	{
	    $tc = Tc::create(Input::get());
	    $tc->sources()->attach(Input::get('sources'));
	    foreach (Input::get('steps') as $v){
	        $step = new TcStep($v);
	        $tc->steps()->save($step);
	    }
	    return $tc;
	}
	
}
