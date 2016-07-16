<?php
//151018
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class RoleController extends BaseController{
    
    //GET /role
    public function index(){
        $roles = Role::orderBy('id','asc')->get();
        echo test;
        return $roles;
    }
}