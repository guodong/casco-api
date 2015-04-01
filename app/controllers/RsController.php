<?php

use Illuminate\Support\Facades\Input;
class RsController extends Controller{

	public function show($id)
	{
		return Rs::find($id);
	}

	public function index()
	{
	    $rss = Rs::where('document_id','=',$_GET['document_id'])->get();
	    foreach ($rss as $v){
	        $v->vat;
	        $v->vatstr;	        
	        $v->rss;
	        $v->result = 1;
	        if(count($v->tcs) == 0 && $v->vatstr_result == 0){
	            $v->result = 0;
	            continue;
	        }
	        foreach ($v->tcs as $vv){
	            if ($vv->result == 2){
	                $v->result = 2;
	                break;
	            }elseif ($vv->result == 0){
	                $v->result = 0;
	            }
	        }
	        if($v->result == 1){
	            if($v->vatstr_result == 2){
	                $v->result = 2;
	            }
	        }

	    }
	    return $rss;
	}
	
	public function update($id)
	{
	    $m = Rs::find($id);
	    $m->vatstr_id = Input::get('vatstr_id');
	    $m->save();
	    $m->vat()->detach();
	    foreach (Input::get('vat') as $v){
	        $m->vat()->attach($v['id']);
	    }
	     
	}
}
