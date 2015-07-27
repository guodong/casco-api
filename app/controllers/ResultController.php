<?php

use Illuminate\Support\Facades\Input;
class ResultController extends BaseController{

	public function show($id)
	{
		return Tc::find($id);
	}

	public function index()
	{
	    $job = Testjob::find(Input::get('job_id'));
	    $results = $job->results;
	    foreach ($results as $v){
	        $sr = json_decode($v->step_result_json)?json_decode($v->step_result_json):[];
	        foreach ($v->tc->steps as $step){
	            $step->result = 0;
	            if (key_exists($step->id, $sr)){
	                $step->result = $sr[$step->id];
	            }
	        }
	        
	        $arr = explode(',',$v->tc->testmethod_id);
	        $tms = [];
	        foreach($arr as $vv){
	            $tmp = Testmethod::find($vv);
	            if($tmp){
	                $tms[] = $tmp;
	            }
	        }
	        $v->tc->testmethods = $tms;
	    }
	    return $results;
	}
	
}