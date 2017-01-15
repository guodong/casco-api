<?php

use Illuminate\Support\Facades\Input;
class TestmethodController extends BaseController {

	public function index()
	{
		$methods = Testmethod::all();
		return $methods;
	}
	
	public function store()
	{
	    $data = Input::get();
	    $md = Testmethod::create($data);
	    return $md->toJson();
	}
	
	// PUT /user/$id
	public function update($id)
	{
	    $method = Testmethod::find($id);
	    $method->update(Input::get());
	    return $method;
	}
	
	public function destroy($id){
	    $method = Testmethod::find($id);
	    $method->destroy($id);
	    return $method;
	}
}
