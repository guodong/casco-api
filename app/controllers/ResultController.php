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
				$sr = ResultStep::where('result_id', $v->id)->where('step_id', json_decode($step->toJson())->id)->first();
				if(!$sr){
					$sr = ResultStep::create(array('result_id'=>$v->id, 'step_id'=>json_decode($step->toJson())->id, 'result'=>0, 'comment'=>''));
				}
				$step->result = $sr->result;
				$step->comment = $sr->comment;
				$step->step_result_id = $sr->id;
			}
			$v->tc->description=$v->tc->description();
			$v->tc->testmethods=$v->tc->dynamic_col('test method');
			/*$arr = (array)json_decode('{'.$v->tc->column.'}',true);
			if(!array_key_exists('test method',$arr))continue;
			(count($test_methods=explode('/',$arr['test method']))>1)||
			(count($test_methods=explode('+',$arr['test method']))>1)||
			(count($test_methods=explode('&',$arr['test method']))>1);
			$ids=Testmethod::whereIn('name',(array)$test_methods)->get()->toArray();
			$v->tc->testmethods = $ids;*/
		}
		return $results;
	}

	public function update($id)
	{
		$r = Result::find($id);
		$r->update(Input::get());
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
				//$s = ResultStep::find($vv['step_result_id']);这样检索不对的好伐
				$s=ResultStep::where('result_id',$r['id'])->where('step_id',$vv['id'])->first();
				if(!$s){
					$s = ResultStep::create(array('result_id'=>$r['id'],'step_id'=>$vv['id'],'result'=>$vv['result'],'comment'=>$vv['comment']));
				}else{
					$s->delete();
					$s = ResultStep::create(array('result_id'=>$r['id'],'step_id'=>$vv['id'],'result'=>$vv['result'],'comment'=>$vv['comment']));
					/*
					 $s->result = $vv['result'];
					 $s->comment = $vv['comment'];
					 $s->save();
					 */
				}
			}
		}
	}

}