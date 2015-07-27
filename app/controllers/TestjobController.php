<?php

use Illuminate\Support\Facades\Input;
class TestjobController extends BaseController{

	public function show($id)
	{
		return Tc::find($id);
	}

	public function index()
	{
	    $jobs = Project::find(Input::get('project_id'))->testjobs;
	    foreach ($jobs as $v){
	        $v->build;
	        $v->tcVersion->document;
	        $v->rsVersion->document;
	    }
	    return $jobs;
	}
	
	public function store()
	{
	    $job = new Testjob(Input::get());
	    $job->save();
	    $v = $job;
	    $v->build;
	    $v->tcVersion->document;
	    $v->rsVersion->document;
	    foreach ($job->tcVersion->tcs as $tc){
	        Result::create(array(
	            'tc_id' => $tc->id,
	            'testjob_id' => $job->id
	        ));
	    }
	    return $job;
	}
	
}