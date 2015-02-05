<?php

use Illuminate\Support\Facades\Input;
class ProjectController extends BaseController {

	//项目创建
	public function store()
	{
		
		$project = new Project(Input::get());
		$project->save();
		
		return $project;
	}

	public function docfile()
	{
		set_time_limit(9999);
		$name = uniqid().'.'.end(explode('.', $file));
		move_uploaded_file($_FILES["file"]["tmp_name"], public_path().'/files/'.$name);
		$url = 'http://192.100.212.31/files/'.$name;
		$u = 'http://192.100.212.33/WebService1.asmx/InputWord?url='.$url;
		$data = file_get_contents($u);
		$d = json_decode($data);
		foreach($d as $v){
			if(empty($v)) continue;
			$rs = new Rs();
			$rs->document_id = $_POST['document_id'];
			$rs->tag = $v->title;
			$rs->allocation = $v->Allocation;
			$rs->category = $v->Category;
			$rs->implement = $v->Implement;
			$rs->priority = $v->Priority;
			$rs->contribution = $v->Contribution;
			$rs->description = $v->description;
			$rs->save();
			foreach($v->Source as $s){
				$source = new Source();
				$source->item_id = $rs->id;
				$source->source = $s;
				$source->save();
			}
		}
		$rt = Rs::all();
		return $rt->toJson();
	}

	//项目列表
	public function index()
	{
		$projects = Project::all();
		return $projects->toJson();
	}
	
	public function show($id)
	{
	    $p = Project::find($id);
	    //$p->graph = json_decode($p->graph);
	    $p->documents;
	    return $p;
	}
	
	public function update($id)
	{
	    $project = Project::find($id);
	    $data = Input::get();
	    if($data['graph']){
	        //$data['graph'] = json_encode($data['graph']);
	    }
	    $project->update($data);
	    return $project;
	}

}
