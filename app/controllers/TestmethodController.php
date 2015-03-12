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
	    $user = Testmethod::find($id);
	    $user->update(Input::get());
	    return $user;
	}
}
