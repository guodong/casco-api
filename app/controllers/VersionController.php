<?php

use Illuminate\Support\Facades\Input;
class VersionController extends BaseController {

	public function index()
	{   
		if(!Input::get('document_id')){$versions=Version::orderBy('updated_at','desc')->first();}
	   else if(!Input::get('newest')) $versions = Version::where('document_id', '=', Input::get('document_id'))->orderBy('updated_at', 'desc')->get();
	    else $versions = Version::where('document_id', '=', Input::get('document_id'))->orderBy('updated_at', 'desc')->first();
	   
	   
	    return $versions;
	}
	
	public function store()
	{
	    $version = Version::create(Input::get());
	    return $version;
	}
	
	public function show($id)   //在线浏览文档
	{
	    $version = Version::find($id);
	    return $version;
	}
	
}
