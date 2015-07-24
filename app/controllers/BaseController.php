<?php

class BaseController extends Controller {

	protected function output($data = NULL, $code = 0, $message = "")
    {
        return array(
            "code" => 0,
            "data" => $data
        );
    }
    
    protected function outputError($message = "", $code = 1)
    {
        return array(
            "code" => $code,
            "data" => $message
        );
    }
    
    protected function auth($token = '')
    {
        $test = 0;
        if($test){
            return User::first();
        }
        if (! Session::has('uid')) {
            echo json_encode(array(
                'code' => -1,
                'data' => 'need login'
            ));
            exit();
        }else {
            return User::find(Session::get('uid'));
        }
    }

}
