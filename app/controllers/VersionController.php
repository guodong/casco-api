<?php

use Illuminate\Support\Facades\Input;
class VersionController extends BaseController {

	public function index()
	{
		if(Input::get('newest')){$versions=Version::orderBy('created_at','desc')->first();$versions&&$versions->document;}
		else if(Input::get('new_update')){$versions = Version::where('document_id', '=', Input::get('document_id'))->orderBy('updated_at', 'desc')->get();
		foreach($versions as $vers){
			$vers->document;
		}
		}else if(Input::get('document_id')){
		$versions =Version::where('document_id', '=', Input::get('document_id'))->orderBy('created_at', 'desc')->first();
		$versions&&$versions->document;
		}
		
		
		return $versions;
	}



	public function store()
	{  
		$data=Input::all();
	   if(Input::get('document')){$data['document_id']=Input::get('document')['id'];}
		$version = Version::create($data);
		return $version;
	}

	public function update($id)
	{
		$version=Version::find($id);
		$data=Input::all();
		$version->fill($data);
		$version->save();
		return $version;
	}

	public function show($id)   //在线浏览文档
	{
		$version = Version::find($id);
		return $version;
	}
	public function  destroy($id)
	{
		$vs=Version::find($id);
		foreach($vs->tcs as $tcs){
			$tcs->delete();
		}
		foreach($vs->rss as $rss){
			$rss->delete();
		}
		Version::destroy($id);//删除versions
		return $vs;
	}
}
