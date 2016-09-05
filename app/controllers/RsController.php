<?php

use Illuminate\Support\Facades\Input;
class RsController extends Controller{

	public function show($id)
	{
		return Rs::find($id);
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

	public  $tags = [];
	public function getTags_down($item)
	{
		//if(!$item||!$item->verison||!$item->version->document){echo 'error';var_dump($item);echo 'shit';var_dump($item->version);return;}
		$sss=$item->srcs();
		foreach ($sss as $v){
			if ($v&&!in_array($v->toArray(), $this->tags)){
				$tmp=$v->toArray();$tmp['mark']='down';
				$this->tags[] = $tmp;
				$this->getTags_down($v);
			}
		}
	}

	public  function getTags_up($item)
	{
		    if(!$item)return;
			$sss=$item->dests();
			//var_dump($sss);
			foreach ($sss as $v){
				if ($v&&!in_array($v->toArray(), $this->tags)){
					$tmp=$v->toArray();$tmp['mark']='up';
					$this->tags[] = $tmp;
					$this->getTags_up($v);
				}
			}
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
			$base=(array)json_decode($v->column,true);
			if(!$base){continue;}
			if (!json_decode($v->vat_json)){
				$v->vat_json = '[]';
				$v->save();
			}
			$obj=array();
			$obj['id']=$v->id;
			$obj['vat']=json_decode($v->vat_json);
			$obj=array_merge($base,$obj);
			$data[]=array_change_key_case($obj,CASE_LOWER);
		}
		//还要解析相应的列名，列名也要发送过去么,怎么办?列名怎样规范化处理呢?
		$version = Version::find (Input::get('version_id'));
		$column=explode(",",$version->headers);
		$columnWidth=(string)(100/(count($column)+1)).'%';
		$columModle=array();
		$fieldsNames=array();
		$columModle[]=(array('dataIndex'=>'tag','header'=>'tag','width'=> $columnWidth));
		$fieldsNames[]=array('name'=>'tag');
		foreach($column as $item){
			$columModle[]=(array('dataIndex'=>$item,'header'=>$item,'width'=> $columnWidth));
			$fieldsNames[]=array('name'=>$item);
		}
	 	return  array('columModle'=>$columModle,'data'=>$data,'fieldsNames'=>$fieldsNames);
	}
	
	
	public function store(){
	    $rs = Rs::create(Input::get());
	    return $rs;
	}

	public function multivats(){
		$rs=Input::get('rs');
		$vat_rs=Input::get('versions');//vatversion
		//var_dump(Input::get('versions'),(array)json_decode(Input::get('verisons')));
		//var_dump((array)$rs,$vat_rs);
		foreach((array)$rs  as $key=>$value){
			$r=Rs::find($value);
			if(!$r)continue;
			$this->getTags_down($r);
			$this->getTags_up($r);
			if(count($this->array_column($this->tags,'id'))<=0){continue;}
			$vata =Rs::whereIn('version_id',$vat_rs)->whereIn('id',$this->array_column($this->tags,'id'))->get();
			$vatb=Tc::whereIn('version_id',$vat_rs)->whereIn('id',$this->array_column($this->tags,'id'))->get();
			//var_dump($vata,$vatb);
			//$vats=array_merge($vata[0],$vatb[0]);
			$array=(array)json_decode($r->vat_json,true);
			foreach($vata as $m=>$n){
				var_dump($n);
				if(!$n)continue;
				if(!in_array($n->id,$this->array_column($array,'id'))){
					$array[]=array('id'=>$n->id,'tag'=>$n->tag,'type'=>$n->version->document->type);
				}
			}
			foreach($vatb as $m=>$n){
				var_dump($n);
				if(!$n)continue;
				if(!in_array($n->id,$this->array_column($array,'id'))){
					$array[]=array('id'=>$n->id,'tag'=>$n->tag,'type'=>$n->version->document->type);
				}
			}
			$r->vat_json=json_encode($array);
			$r->save();
		}
		
		return array('success'=>true,'msg'=>'批量编辑玩成!');
	}
	
	public function destroy($id){
			$rs=Rs::find($id);
			$rs->delete();
			return $rs;
	}


	//走的是put头
	public function update($id)
	{
		$data = Input::get();
		$m = Rs::find($id);
		$cols=$m->column();
		$column=json_decode('{'.$data['column'].'}',true);
		foreach((array)$column as $key=>$value){
		if(array_key_exists($key,$cols)){
			$cols[$key]=$value;
		}}
		$m->column=json_encode($cols);
		$m->tag=$data['tag'];
		$m->vat_json = json_encode($data['vat']);
		$m->save();
		return $m;
	}
}
