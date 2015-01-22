<?php

class TcController extends Controller{

	public function show($id)
	{
		return Rs::find($id);
	}

	public function index()
	{
	    $tcs = Tc::where('document_id', '=', $_GET['document_id'])->get();
        
        $tcs->each(function($tc){
            $tc->steps;
            $sources = array();
            foreach ($tc->rss as $rs){
                $sources[] = $rs->id;
            }
            $tc->sources = $sources;
        });
        return $tcs;
	}
	
	public function store()
	{
	    $d = file_get_contents('php://input');
	    $data = json_decode($d);
	    $tc = new Tc();
	    $tc->document_id = $data->doc_id;
	    $tc->title = $data->title;
	    $tc->dsc = $data->dsc;
	    $tc->test_method = $data->test_method;
	    $tc->pre_condition = $data->pre_condition;
	    $tc->save();
	    //$tc->rss()->sync(array($data->sources));
	    $tc->rss()->attach($data->sources);
	    foreach ($data->steps as $v) {
	        $test_step = new TestStep();
	        $test_step->tc_id = $tc->id;
	        $test_step->num = $v->num;
	        $test_step->actions = $v->actions;
	        $test_step->exp_res = $v->exp_res;
	        $test_step->save();
	    }
	    return $tc->toJson();
	}
	
}
