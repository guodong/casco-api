<?php
//151016
class BaseController extends Controller {
    
    //成功
	protected function output($data = NULL, $code = 0, $message = "")
    {
        return array(
            "code" => 0,
            "data" => $data
        );
    }
    
    //报错
    protected function outputError($message = "", $code = 1)
    {
        return array(
            "code" => $code,
            "data" => $message
        );
    }
    
    //用户认证
    protected function auth($token = '')
    {
        $test = 0;  //???哪里改变值
        if($test){
            return User::first();
        }
        if (! Session::has('uid')) {    //session不存在
            echo json_encode(array(
                'code' => -1,
                'data' => 'need login'
            ));
            exit();
        }else { 
            return User::find(Session::get('uid')); //session存在
        }
    }

}
