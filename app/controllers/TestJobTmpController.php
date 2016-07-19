<?php
use Illuminate\Support\Facades\Input;
class TestjobTmpController extends BaseController {
	
public function index()
	{	
		if(!$id=Input::get('project_id'))return [];
		$tmp=TestjobTmp::where('project_id','=',$id)->get();
		return $tmp;
	}

public function update()
	{
		$t = TestjobTmp::find(Input::get('id'));
		$t->update(Input::get());
		return $t;
		
	}
	
public function  destroy($id)
	{	
		if(!$id)return [];
		$tmp=TestjobTmp::find($id);
		file_exists($tmp->path)?unlink($tmp->path):null;
		$tmp->destroy($id);
		return $tmp;
	}
}
?>