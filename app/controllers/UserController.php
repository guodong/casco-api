<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class UserController extends BaseController {

    // GET /user
	public function index()
	{
		$users = User::orderBy('created_at', 'desc')->get();
		$users->each(function($u){
		    $u->projects;  //add it!too much data!
		});
		return $users;
	}
	
	// GET /user/$id
	public function show($id)
	{
	    $user = User::find($id);//原来两者url走向并不一样
	    if($user){
	        $user->projects;    
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
	    
	    $projects_id=Input::get('project');//发送过来的就是数组了
	 //   var_dump($projects_id);
	    foreach($projects_id  as $id){
	    //用这种方法生成insert简直无语了	
	    $id = DB::table('project_user')->insertGetId(
	    array('project_id' => $id, 'user_id' => $user->id)); 
	    }
	    
	    
	    
	    return $user->toJson();
	}
	public function is_md5($password) {
                // echo 'ans is '.preg_match("/^[a-z0-9]{32}$/",$password);
		return preg_match("/^[a-z0-9]{32}$/", $password);
        }
	// PUT /user/$id
	public function update($id)
	{
	    $user = User::find($id);
        $data=Input::get();
        $va=preg_match("/^[a-z0-9]{32}$/",(Input::get('password')));
	    if(!$va){$data['password']=md5(Input::get('password'));}
	    $user->update($data);
	    //还要更新projects表哦
	    
	    if($projects_id=Input::get('project')){
	    $affectedrows=ProjectUser::where('user_id',$user->id)->delete();
	    foreach($projects_id  as $id){
	    //存在一个疑问怎样更新比较好的方法啊,全部删掉再去insert?
	    $id = DB::table('project_user')->insertGetId(
	    array('project_id' => $id, 'user_id' => $user->id)); 
	    }
	    
	    }//修改时有有可能不发送此字段
	    
	    return $user;
	}
	
	/*
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
*/
	public function destroy($id){
            $user=User::find($id);
            //还要删掉其对应的project_user记录
            if($user->projects_user){
            	$user->projects_user->each(function($u){
            		
            		$u->delete();
            	});
            	
            }
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