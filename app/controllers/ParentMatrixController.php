<?php

use Illuminate\Support\Facades\Input;

class ParentMatrixController extends BaseController {

 public function index(){
 	
 	$items=[];
 	//列名与字段一一对应起来吧
 	if($id=Input::get('id')){
 		$items=ParentMatrix::where('verification_id','=',$id)->orderBy('Parent Requirement Tag','asc')->get()->toArray();
 	}
   $columModle=array();
   foreach(json_decode($items[0]['column'],true) as $key=>$value){
   	array_push($columModle,array('dataIndex'=>$key,'header'=>$key,'width'=>140));
   }
   
    $data=[];
   foreach($items as $item){
   	$column=json_decode($item['column'],true);
   	$column=array_merge((array)$item,$column);
   	array_push($data,$column);
   	
   }
    
   return  array('columModle'=>$columModle,'data'=>$data);
 }
 
 
 
 public function  store(){
 	
 	
 	
 }
 
 
 
 public function  show($id){
 	
 	
 	
 	
 	
 }
 
 
 public function update(){
 	
 	
 	
 	
 	
 	
 }
 
 
 
 
 
 
 
}



?>