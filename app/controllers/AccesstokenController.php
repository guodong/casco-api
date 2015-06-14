<?php
class AccesstokenController extends BaseController {

	public function index()
	{
		$user = User::whereRaw('account = ? and password = ?', array(Input::get('account'), md5(Input::get('password'))))->first();

		if($user){
		    $token = md5($user->id.time());
		    $user->accesstoken = $token;
		    Session::put('uid', $user->id);
		    return $user;
		}else{
		    return json_encode(array('error'=>1));
		}
	}
	
}
