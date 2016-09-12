<?php

use Illuminate\Support\Facades\Input;
class RsController extends Controller{

	public function show($id)
	{
		return Rs::find($id);
	}

	private $tags = [];
	private $srcs=[];
	private $dests=[];
	public function getTags_down($item)
	{
		if(!$item)return;//if(!$item||!$item->verison||!$item->version->document){echo 'error';var_dump($item);echo 'shit';var_dump($item->version);return;}
		$sss=$item->srcs();
		foreach ($sss as $v){
			if ($v&&!in_array($v->toArray(), $this->tags)){
				$tmp=$v->toArray();
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
				$tmp=$v->toArray();
				$this->tags[] = $tmp;
				$this->getTags_up($v);
			}
		}
	}


	public function array_column($input,$column_key,$index_key=''){

		if(!is_array($input)) return [];
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

	function doc_down($sr,$mark){
		if(!$sr)  return [];
		($mark=='src')?$this->srcs[]=$sr->toArray():$this->dests[]=$sr->toArray();
		foreach($sr->src() as $item){
			$this->doc_down($item,$mark);
		}
	}

	function doc_up($sr,$mark){
		if(!$sr)  return [];
		($mark=='src')?$this->srcs[]=$sr->toArray():$this->dests[]=$sr->toArray();
		foreach($sr->dest() as $item){
			$this->doc_up($item,$mark);
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

	public function get_vat($rsitem,$docs,$rt){

			if (!$docs||!$rsitem)return [];
			$version = $docs->latest_version();
			if ($version == null) {
				return [];
			}
			$this->doc_up($docs,'dest');
			$this->doc_down($docs,'dest');
			$this->doc_up($rsitem->version->document,'src');
			$this->doc_down($rsitem->version->document,'src');
			$this->getTags_down($rsitem);
			$this->getTags_up($rsitem);
			//var_dump($this->srcs);
			$merge=[];
			foreach($this->srcs as $t){
				if(in_array($t,$this->dests))$merge[]=$t;
			}
			//var_dump($merge);
			if(count($merge)==0){
				return  $rt;
			}else if(count($merge)>1){

			}else if(count($merge)==1){//说明有公共焦点哦,非父子关系
					
				//var_dump($merge);
				$tmp=Document::find($merge['id']);
				$ver =$tmp->latest_version();
				if (!$ver) {
					return $rt;
				}
				switch ($tmp->type) {

					case 'rs':
						if(!$rsitem||count($this->array_column($this->tags,'id'))<=0){$items=[];break;}
						$middles =Rs::where('version_id','=', $ver->id)->whereIn('id',$this->array_column($this->tags,'id'))->get();
						break;
					case 'tc':
						if(!$rsitem||count($this->array_column($this->tags,'id'))<=0){$items=[];break;}
						$middles =Tc::where('version_id','=',$ver->id)->whereIn('id',$this->array_column($this->tags,'id'))->get();
						break;
					default:
						$middles= array();
				}//switch
				$this->tags=[];
				foreach($middles as $key=>$value){
					//只可能往下走
				 $this->getTags_down($value);
				}
			}//else
			switch ($docs->type) {
				case 'rs':
					if(count($this->array_column($this->tags,'id'))<=0){$items=[];break;}
					$items =Rs::where('version_id','=', $version->id)->whereIn('id',$this->array_column($this->tags,'id'))->get();
					break;
				case 'tc':
					if(count($this->array_column($this->tags,'id'))<=0){$items=[];break;}
					$items =Tc::where('version_id','=',$version->id)->whereIn('id',$this->array_column($this->tags,'id'))->get();
					break;
				default:
					$items = array();
			}
			foreach($items as $v) {
				$rt[] = array(
                    'tag' => $v->tag,
                    'id' => $v->id,
                    'type' => $v->version->document->type
				);
			}
			return $rt;
		}

		
		public function multivats(){
			$rs=Input::get('rs');
			$vat_rs=Input::get('versions');
			foreach((array)$rs  as $key=>$value){
				$rsitem = Rs::find($value);
				if(!$rsitem)continue;
				foreach($vat_rs as  $a=>$b){
					if(!$version=Version::find($b)) continue;
					$docs=$version->document;
					$array=(array)json_decode($rsitem->vat_json,true);
					$vats=$this->get_vat($rsitem,$docs,$array);
					$rsitem->vat_json=json_encode($vats);
					var_dump($rsitem->vat_json);
				}
					$rsitem->save();
			}
			return array('success'=>true,'msg'=>'批量编辑玩成!');
		}

		public function destroy($id){
			$rs=Rs::find($id);
			$rs->delete();
			return $rs;
		}

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
