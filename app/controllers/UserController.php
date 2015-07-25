<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
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
	public function destroy($id){
            $user=User::find($id);
            $user->destroy($id);
            return $user;
             
        }
	public function login()
	{
	    $user = User::whereRaw('account = ? and password = ?', array(Input::get('account'), md5(Input::get('password'))))->first();
	    
	    if($user){
	        Session::put('uid', $user->id);
	        return $this->output($user);
	    }else{
	        return $this->outputError('login error');
	    }
	}
	
	public function session()
	{
	    if (Session::has('uid')){
	        return $this->output(User::find(Session::get('uid')));
	    }else{
	        return $this->outputError('not login');
	    }
	}
}
