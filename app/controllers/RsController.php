<?php

use Illuminate\Support\Facades\Input;
class RsController extends Controller{

	public function show($id)
	{
		return Rs::find($id);
	}

	public function  striplashes($item){

		$item=preg_replace("/([\r\n])+/", "", $item);//过滤掉一种奇葩编码,shit!
		$item=str_replace('\'',"'",$item);
		return  $item;
	}
	public function array_column($input,$column_key,$index_key=''){

		if(!is_array($input)) return;
		$results=array();
		if($column_key===null){
			if(!is_string($index_key)&&!is_int($index_key)) return false;
			foreach($input as $_v){
				if(array_key_exists($index_key,$_v)){
					$results[$_v[$index_key]]=$_v;
				}
			}
			if(empty($results)) $results=$input;
		}else if(!is_string($column_key)&&!is_int($column_key)){
			return false;
		}else{
			if(!is_string($index_key)&&!is_int($index_key)) return false;
			if($index_key===''){
				foreach($input as $_v){
					if(is_array($_v)&&array_key_exists($column_key,$_v)){
						$results[]=$_v[$column_key];
					}
				}
			}else{
				foreach($input as $_v){
					if(is_array($_v)&&array_key_exists($column_key,$_v)&&array_key_exists($index_key,$_v)){
						$results[$_v[$index_key]]=$_v[$column_key];
					}
				}
			}

		}
		return $results;




	}


	public function index()
	{
		$rsv = Input::get('document_id')?Document::find(Input::get('document_id'))->latest_version():(Input::get('version_id')?Version::find(Input::get('version_id')):'');
		if (!$rsv){
			return '[]';
		}
		$rss = $rsv->rss;
		if(Input::get('act')=="stat"){
			$result=[];
			foreach ($rss as $v){
				if (!json_decode($v->vat_json)){
					$v->vat_json = '[]';
					$v->save();
				}
				$res['id']=$v->id;
				$res['tag']=$v->tag;
				$res['vat']=json_decode($v->vat_json);
				$res['rss']=$v->rss();
				$res['tcs']=$v->tcs();
				$result[]=$res;

			}
			return  $result;

		}

		$data=array();
		foreach ($rss as $v){
			$v->column=$this->striplashes($v->column);
			//if(preg_match('/0051/',$v->tag)){var_dump($v->column);exit;}
			$base=json_decode('{"id":"'.$v->id.'","tag":"'.$v->tag.($v->column?('",'.$v->column):'"').'}',true);
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
		$version = Version::find (Input::get('version_id'));
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
