<?php

use Illuminate\Support\Facades\Input;
class RsController extends Controller{

	public function show($id)
	{
		return Rs::find($id);
	}

	public function index()
	{
	    $rsv = Input::get('document_id')?Document::find(Input::get('document_id'))->latest_version():(Input::get('version_id')?Version::find(Input::get('version_id')):'');
	    if (!$rsv){
	        return '[]';
	    }
	    $rss = $rsv->rss;
	    foreach ($rss as $v){
	        if (!json_decode($v->vat_json)){
	            $v->vat_json = '[]';
	            $v->save();
	        }
	        $v->vat = json_decode($v->vat_json);
	        $v->rss = $v->rss();
	        $v->tcs = $v->tcs();

	    }
	    return $rss;
	}
	
	public function update($id)
	{
	    $data = Input::get();
	    $m = Rs::find($id);
	    $m->vat_json = json_encode($data['vat']);
	    $m->save();
	}
}
