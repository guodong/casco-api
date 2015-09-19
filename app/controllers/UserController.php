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
	public function is_md5($password) {
                // echo 'ans is '.preg_match("/^[a-z0-9]{32}$/",$password);
		return preg_match("/^[a-z0-9]{32}$/", $password);
        }
	// PUT /user/$id
	public function update($id)
	{
	    $user = User::find($id);/*
              echo 'ans is '.preg_match("/^[a-z0-9]{32}$/",Input::get('password'));
          */


           $data=Input::get();
            $va=preg_match("/^[a-z0-9]{32}$/",(Input::get('password')));
	    if(!$va){$data['password']=md5(Input::get('password'));}
	    $user->update($data);
	    return $user;
	}
	public function userpros(){
	    
	     $user=User::find(Input::get('user_id'));
	     $pros=$user->Projects;
		 return $pros;
	
	}
	
	public function userprosdel(){
		
		$pros_user=ProjectUser::where('project_id','=',Input::get('project_id'))->where('user_id','=',Input::get("user_id"))->get();
		if($pros_user!="[]"){
		foreach($pros_user as $rel){$rel->delete();}
		return '{"code":1,"Msg":"delete success!"}';
		}
		else {
			return  '{"code":"0","Msg":"nothing to delete!"}';
		}
	
	}
	public function destroy($id){
            $user=User::find($id);
            $user->destroy($id);
            return $user;
             
        }
	public function login()
	{   
		
		
		$user=User::whereRaw('account = ?',array(Input::get('account')))->first();
		
		if($user){
			if($user->islock){
				
				return $this->outputError('your account has been locked!');
			}else{
				
				if($user->password==md5(Input::get('password'))){
					
					 Session::put('uid', $user->id);
	                 return $this->output($user);
				}else{
					
					
					return $this->outputError('your password error!');
					
				}
				
				
				
				
				
			}
			
			
			
				
			
		}else{
			
			return $this->outputError('account error!');
		}
		
		
	}
	public function logout(){
         
        if(Session::has('uid')){
           Session::forget('uid');  
           //echo 'you have logout';
           return  $this->output('logout');
         }else {
           return $this->output('you are not login');
        }
        }//logout
	public function session()
	{
	    if (Session::has('uid')){
	        return $this->output(User::find(Session::get('uid')));
	    }else{
	        return $this->outputError('not login');
	    }
	}
}