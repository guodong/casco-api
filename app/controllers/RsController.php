<?php

use Illuminate\Support\Facades\Input;
class RsController extends Controller{

	public function show($id)
	{
		return Rs::find($id);
	}

	public function index()
	{
	    $rsv = Version::find(Input::get('version_id'));
	    if (!$rsv){
	        return '[]';
	    }
	    $rss = $rsv->rss;
	    foreach ($rss as $v){
	        $v->vat;
	        $v->vatstr;	        
	        $v->rss = $v->rss();
	        $v->tcs = $v->tcs();

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
