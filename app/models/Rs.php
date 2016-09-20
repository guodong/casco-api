<?php
class Rs extends BaseModel {

	protected $table = 'rs';
	protected $fillable = array('tag','column','vat_json','created_at','updated_at','version_id');


	public function  column(){
		$column=[];
		if($this->column){
			$column=array_change_key_case((array)json_decode($this->column,true),CASE_LOWER);
		}else if(array_key_exists('column',$this["original"])){
			$column=array_change_key_case((array)json_decode($this["original"]["column"],true),CASE_LOWER);
		}
		//var_dump($column);
		return $column;
	}
	
	public function sources()
	{
		$arr =$this->column();
		if(!$arr)return [];
		array_key_exists('source',$arr)?preg_match_all('/\[.*?\]/i',preg_replace('/\s/','',$arr['source']),$matches):($matches[0]=[]);
		return $matches[0];
	}

	public function description(){
		$arr =$this->column();
		if(!$arr) return []; // {var_dump($this->column);exit;}
		if(array_key_exists('description',$arr)){
			return $arr['description'];
		}else if(array_key_exists('test case description',$arr)){
			return  $arr['test case description'];
		}else if(array_key_exists('test description',$arr)){
			return  $arr['test description'];
		}else return null;

	}
	
	public function dests()
	{
		$tcs = [];
		$srcs = $this->version->document->dest();
		if(!$matches=$this->sources())return [];
		foreach($srcs as $src){
			switch($src->type){
				case 'tc':
					$tmp = Tc::where('version_id', '=', $src->latest_version()?$src->latest_version()->id:null)->whereIn('tag',$matches)->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v)
					{
						!in_array($v,$tcs)?$tcs[]=$v:null;
					};break;
				case  'rs':
					$tmp = Rs::where('version_id', '=', $src->latest_version()?$src->latest_version()->id:null)->whereIn('tag',$matches)->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v)
					{
						!in_array($v,$tcs)?$tcs[]=$v:null;
					};break;
				default:
			}//switch
		};//foreach
		return $tcs;

	}
	public function srcs()
	{
		$result = [];
		$tag=preg_replace('/[\[\]]/','',$this->tag);
		$srcs = $this->version->document->src();
		foreach($srcs as $src){
			switch($src->type){
				case 'tc':
					$tmp = Tc::where('version_id','=', $src->latest_version()?$src->latest_version()->id:null)->where('column', 'like', '%"source":%'.$tag.'%')->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v){
						if(!in_array($v,$result))$result[]=$v;
					};break;
				case  'rs':
					$tmp = Rs::where('version_id','=', $src->latest_version()?$src->latest_version()->id:null)->where('column', 'like', '%"source":%'.$tag.'%')->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					//var_dump($tmp,$src->name,$src->latest_version()->id,$this->tag);//exit;
					foreach ($tmp as $v){
						if(!in_array($v,$result))$result[]=$v;
					};break;
				default:
			}//switch
		};//foreach
		//while($tmp=array_shift($result)){var_dump($tmp->version,$tmp->version->document->name);};
		return $result;
		//return $this->belongsToMany('Tc', 'tc_source', 'source_id', 'tc_id');
	}

	//V模型中纵向引用该rs的rs
	public function rss()
	{
		$result = [];
		$tag=preg_replace('/[\[\]]/','',$this->tag);
		$srcs = $this->version->document->src();
		foreach($srcs as $src){
			switch($src->type){
				case  'rs':
					$tmp = Rs::where('version_id','=', $src->latest_version()?$src->latest_version()->id:null)->where('column', 'like', '%"source":%'.$tag.'%')->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					//var_dump($tmp,$src->name,$src->latest_version()->id,$this->tag);//exit;
					foreach ($tmp as $v){
						if(!in_array($v,$result))$result[]=$v;
					};break;
				default:
			}//switch
		};//foreach
		return $result;
	}
	
	public function tcs()
	{
		$result = [];
		$tag=preg_replace('/[\[\]]/','',$this->tag);
		$srcs = $this->version->document->src();
		foreach($srcs as $src){
			switch($src->type){
				case 'tc':
					$tmp = Tc::where('version_id','=', $src->latest_version()?$src->latest_version()->id:null)->where('column', 'like', '%"source":%'.$tag.'%')->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v){
						if(!in_array($v,$result))$result[]=$v;
					};break;
				default:
			}//switch
		};//foreach
		//while($tmp=array_shift($result)){var_dump($tmp->version,$tmp->version->document->name);};
		return $result;
	}

	public function vat()
	{
		return $this->belongsToMany('Tag', 'rs_vat', 'rs_id', 'vat_id');
	}

	public function vatstr()
	{
		return $this->belongsTo('Vatstr');
	}

	public function isNewest(){

		return $this->Version()->id==$this->Version()->document->latest_version()->id;
	}
	public function version()
	{
		return $this->belongsTo('Version');
	}

	public function  vats(){
		$arr=array();
		foreach(array($this->vat_json)  as  $vat){
			$vat=json_decode($vat);
			$vat&&$arr[]=$vat;
		}
		return $arr;
	}

	public function  column_text(){
		$column=$this->column();$ans=null;
		foreach($column as  $key=>$value){
			if(strtolower($key)=='description' ||strtolower($key)=='test case description'||strtolower($key)=='test description'){
				$ans=$value.'\n'.$ans;}
				else
				$ans.='#'.$key.':'.$value.'\n';
		}
		return $ans;
	}

}
