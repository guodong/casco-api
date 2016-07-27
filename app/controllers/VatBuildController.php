<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class VatBuildController extends BaseController{

	public function index(){
		
		if(Input::get('id')){
			$vatbuild=VatBuild::find(Input::get('id'));
			$vatbuild&&($vatbuild->tcVersion||$vatbuild->rsVersions);
			return $vatbuild;
			
		}
		$vats = Project::find(Input::get('project_id'))->vatbuilds;
		if(!Input::get('document_id')){
			foreach($vats as $v)
			{$v->tcVersion&&$v->tcVersion->document;
				foreach($v->rsVersions as $versions){
					$versions->document;
				}
			}
			return  $vats;
		}
		foreach ($vats as $v){
			if(!$v) continue;
			if($v->tcVersion && $v->tcVersion->document->id == Input::get('document_id')){
				$v->tcVersion->document;
				$rsVersions = $v->rsVersions ? $v->rsVersions : null;
				foreach($rsVersions as $vv){
					$vv->document;
				}
			}
		}
		return $vats;
	}
	 
	public function store(){
		$vats = VatBuild::create(Input::get());
		foreach (Input::get('rs_versions') as $v){
			$vats->rsVersions()->attach($v['rs_version_id']);
		}

		return $vats;
	}
	 
	public function destroy($id){
		$vats = VatBuild::find($id);
		//        var_dump($vats->vatRss);exit;
		foreach ($vats->vatRss as $v){
			$v->delete();
		}
		$vats->destroy($id);
		return $vats;
	}
	 
}

