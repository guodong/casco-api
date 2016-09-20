<?php

use Illuminate\Support\Facades\Input;
class TestController extends Controller{

    public function index()
    {
     
     $rs=Rs::all();
	
 	foreach($rs as $key=>$value){

	if(!preg_match('/\[.*?\]/i',$value->tag)){
		var_dump($value->tag);
		$m=Rs::find($value->id);
		$m->tag='['.$value->tag.']';$m->save();
	}   
	}//foreach 	
    }


}
