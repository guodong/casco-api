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
	    foreach (Input::get('sources') as $v){
	        $tc->sources()->attach($v['id']);
	    }
	    
	    foreach (Input::get('steps') as $v){
	        $step = new TcStep($v);
	        $tc->steps()->save($step);
	    }
	    return $tc;
	}
	
	public function update($id)
	{
	    $m = Tc::find($id);
	    $m->update(Input::get());
	    $m->sources()->detach();
	    foreach (Input::get('sources') as $v){
	        $m->sources()->attach($v['id']);
	    }
	    $m->steps()->delete();
	    foreach (Input::get('steps') as $v){
	        $step = new TcStep($v);
	        $m->steps()->save($step);
	    }
	    return $m;
	}
	
}
