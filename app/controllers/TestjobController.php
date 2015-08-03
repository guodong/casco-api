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
	        foreach ($v->rsVersions as $vv){
	            $vv->document;
	        }
	    }
	    return $jobs;
	}
	
	public function store()
	{
	    $job = new Testjob(Input::get());
	    $job->save();
	    foreach (Input::get('rs_versions') as $v){
	        $job->rsVersions()->attach($v['rs_version_id']);
	    }
	    $v = $job;
	    $v->build;
	    $v->tcVersion->document;
	    foreach ($v->rsVersions as $vv){
	        $vv->document;
	    }
	    foreach ($job->tcVersion->tcs as $tc){
	        Result::create(array(
	            'tc_id' => $tc->id,
	            'testjob_id' => $job->id
	        ));
	    }
	    return $job;
	}
	
	public function update()
	{
	    $t = Testjob::find(Input::get('id'));
	    $t->update(Input::get());
	    return $t;
	}
	
	public function rsversion()
	{
	    $job = Testjob::find(Input::get('job_id'));
	    $rsvs = $job->rsVersions;
	}
	
}