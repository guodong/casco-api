<?php

use Illuminate\Support\Facades\Input;
class BuildController extends Controller{

    public function index()
    {
        $builds = Build::where('project_id', '=', Input::get('project_id'))->get();
        return $builds;
    }
    
	public function show($id)
	{
		return Build::find($id);
	}
	
	public function store()
	{
	    $build = Build::create(Input::get());
	    return $build;
    } 
    
	public function update($id)
	{
		$build = Build::find($id);
        $data=Input::get();
	    $build->update($data);
	    return $build;
	}
	
	public function  destroy($id)
    {
             $build=Build::find($id);
             $build->destroy($id);
             return  $build;

     }



	
}
