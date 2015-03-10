<?php

use Illuminate\Support\Facades\Input;
class UserController extends BaseController {

    // GET /user
	public function index()
	{
		$users = User::orderBy('created_at', 'desc')->get();
		$users->each(function($u){
		    $u->projects;
		});
		return $users;
	}
	
	// GET /user/$id
	public function show($id)
	{
	    $user = User::find($id);
	    if($user){
	        $user->school;
	        $user->major;
	        $user->tags;
	        return $user;
	    }else{
	        return json_encode(array('error'=>1, 'msg'=>'no user'));
	    }
	}

	// POST /user
	public function store()
	{
	    $data = Input::get();
	    $data['password'] = md5(Input::get('password'));
	    $user = User::create($data);
	    return $user->toJson();
	}
	
	// PUT /user/$id
	public function update($id)
	{
	    $user = User::find($id);
	    $user->update(Input::get());
	    return $user;
	}
}
