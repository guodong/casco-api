<?php
class Tc extends BaseModel {

	protected $table = 'tc';
	protected $fillable = array('tag','column','checklog','robot','version_id','result');


	public function steps()
	{
		return $this->hasMany('TcStep')->orderBy('num')->orderBy('created_at','asc');
	}

	public function  column(){
		$column=[];
		if($this->column){
			$column=array_change_key_case((array)json_decode($this->column,true),CASE_LOWER);
		}else if(array_key_exists('column',$this["original"])){
			$column=array_change_key_case((array)json_decode($this["original"]["column"],true),CASE_LOWER);
		}
		return $column;
	}
	public function description(){

		$arr =$this->column();
		if(!$arr) return []; //
		if(array_key_exists('description',$arr)){
			return $arr['description'];
		}else if(array_key_exists('test case description',$arr)){
			return  $arr['test case description'];
		}else if(array_key_exists('test description',$arr)){
			return  $arr['test description'];
		}else return null;

	}
	public function  dynamic_col($key){

		$arr =$this->column();
		if(!$arr)return null;
		if(array_key_exists($key,$arr)){
			return $arr[$key];
		}else return null;
	}

	public function sources()
	{
		$arr =$this->column();
		if(!$arr)return [];
		array_key_exists('source',$arr)?preg_match_all('/\[.*?\]/i',preg_replace('/\s/','',$arr['source']),$matches):($matches[0]=[]);
		return $matches[0];
	}
	public function dests()
	{
		$tcs = [];
		$srcs = $this->version->document->dest();
		if(!$matches=$this->sources())return [];
		foreach($srcs as $src){
			switch($src->type){
				case 'tc':
					$tmp = Tc::where('version_id', '=',  $src->latest_version()?$src->latest_version()->id:null)->whereIn('tag',$matches)->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v)
					{
						!in_array($v,$tcs)?$tcs[]=$v:null;
					};break;
				case  'rs':
					$tmp = Rs::where('version_id', '=',  $src->latest_version()?$src->latest_version()->id:null)->whereIn('tag',$matches)->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					//var_dump($src->latest_version()->id,$matches);
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
		$result = [];$this->tag=preg_replace('/[\[\]]/','',$this->tag);
		//if(!$this->version||!$this->version->document)return $result;
		$srcs = $this->version->document->src();
		foreach($srcs as $src){
			switch($src->type){
				case 'tc':
					$tmp = Tc::where('version_id','=', $src->latest_version()?$src->latest_version()->id:null)->where('column', 'like', '%"source":%'.$this->tag.'%')->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v){
						if(!in_array($v,$result))$result[]=$v;
					};break;
				case  'rs':
					$tmp = Rs::where('version_id','=', $src->latest_version()?$src->latest_version()->id:null)->where('column', 'like', '%"source":%'.$this->tag.'%')->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v){
						if(!in_array($v,$result))$result[]=$v;
					};break;
				default:
			}//switch
		};//foreach
		return $result;
		//return $this->belongsToMany('Tc', 'tc_source', 'source_id', 'tc_id');
	}

	public function results()
	{
		return $this->hasMany('Result');
	}
	public function version()
	{
		return $this->belongsTo('Version');
	}

}
