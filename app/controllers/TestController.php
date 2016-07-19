<?php

use Illuminate\Support\Facades\Input;
class TestController extends Controller{

    public function index()
    {
        $tc = Tc::all();
        foreach($tc as $tcs){
        	$tcs->column=str_replace('\\','',$tcs["original"]["column"]);
        	$tcs->save();
        }
        $tc = Rs::all();
        foreach($tc as $tcs){
        	$tcs->column=str_replace('\\','',$tcs["original"]["column"]);
        	$tcs->save();
        }
        var_dump('hehedfa');
        
    }


}
