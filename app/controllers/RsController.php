<?php

use Illuminate\Support\Facades\Input;
class RsController extends Controller{

	public function show($id)
	{
		return Rs::find($id);
	}

	public function index()
	{
	    $rsv = Input::get('document_id')?Document::find(Input::get('document_id'))->latest_version():(Input::get('version_id')?Version::find(Input::get('version_id')):'');
	    if (!$rsv){
	        return '[]';
	    }
	    $rss = $rsv->rss;
	    if(Input::get('act')=="stat"){
	    foreach ($rss as $v){
	        if (!json_decode($v->vat_json)){
	            $v->vat_json = '[]';
	            $v->save();
	        }
	        $v->vat = json_decode($v->vat_json);
	        $v->rss = $v->rss();
	        $v->tcs = $v->tcs();

	    }
         return  $rss;
	    }

	    $data=array();
	    
	    foreach ($rss as $v){
	    $base=json_decode('{"id":"'.$v->id.'","tag":"'.$v->tag.($v->column?('",'.$v->column):'"').'}',true);//票漂亮哦
	    if(!$base)continue;
	    if (!json_decode($v->vat_json)){
	            $v->vat_json = '[]';
	            $v->save();
	        }
	    $obj=array();
	    $obj['vat']=json_decode($v->vat_json);
	    $obj=array_merge($base,$obj);
	    
	    $data[]=$obj;
	    
        }
	  //还要解析相应的列名，列名也要发送过去么,怎么办?列名怎样规范化处理呢?
	   $version = Version::find ( Input::get ( 'version_id' ) );
	   $column=explode(",",$version->headers);
	   
	   $columModle=array();
	   $fieldsNames=array();
	   $columModle[]=(array('dataIndex'=>'tag','header'=>'tag','width'=> 140));
	    $fieldsNames[]=array('name'=>'tag');
	   foreach($column as $item){
	   
	    $columModle[]=(array('dataIndex'=>$item,'header'=>$item,'width'=> 140));
	    $fieldsNames[]=array('name'=>$item);
	   
	   }
	    
	   return  array('columModle'=>$columModle,'data'=>$data,'fieldsNames'=>$fieldsNames);
	  // return  json_encode(array('columModle'=>$columModle));
	   
	   
	   /* foreach($rsv as $col){
	    	
	    	
	     $cols=explode(";",$col->column);
	     
	     
	     
	     
	    	
	    	
	    	
	    }
*/
	    
	  
	    
	    
	    
	    
	    
	    
	    
	     
	}
	public function store($id){
		
		
		
		
	}
	
	
	
	
	//走的是put头
	public function update($id)
	{
	    $data = Input::get();
	    $m = Rs::find($id);
	    $m->column=$data['column'];
	    $m->tag=$data['tag'];
	    $m->vat_json = json_encode($data['vat']);
	    $m->save();
	}
}
