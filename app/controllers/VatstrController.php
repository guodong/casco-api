<?php

use Illuminate\Support\Facades\Input;
class VatstrController extends Controller{

	public function show($id)
	{
		return Tc::find($id);
	}

	public function index()
	{
	    $project = Project::find(Input::get('project_id'));
	    $vatstrs = $project->vatstrs()->orderBy('name')->get();
	    return $vatstrs;
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
