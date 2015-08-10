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
	            $sr = ResultStep::where('result_id', $v->id)->where('step_id', $step->id)->first();
	            if(!$sr){
	                $sr = ResultStep::create(array('result_id'=>$v->id, 'step_id'=>$step->id, 'result'=>0, 'comment'=>''));
	            }
	            $step->result = $sr->result;
	            $step->comment = $sr->comment;
	            $step->step_result_id = $sr->id;
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
	
	public function update()
	{
	    Result::update(Input::get());
	}
	
	public function updateall()
	{
	    foreach (Input::get('results') as $v){
	        $r = Result::find($v['id']);
// 	        $r->begin_at = $v['begin_at'];
// 	        $r->end_at = $v['end_at'];
// 	        $r->result = $v['result'];
// 	        $r->cr = $v['cr'];
	        $r->update($v);
	        foreach ($v['steps'] as $vv){
	            $s = ResultStep::find($vv['step_result_id']);
	            if(!$s){
	                $s = ResultStep::create(array('result_id'=>$r['id'],'step_id'=>$vv['id'],'result'=>$vv['result'],'comment'=>$vv['comment']));
	            }else{
	                $s->result = $vv['result'];
	                $s->comment = $vv['comment'];
	                $s->save();
	            }
	        }
	    }
	}
	
}