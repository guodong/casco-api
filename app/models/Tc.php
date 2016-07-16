<?php
class Tc extends BaseModel {

	protected $table = 'tc';
	protected $fillable = array('tag','column','checklog','robot','version_id', 'description', 'testmethod_id', 'pre_condition', 'result','source_json');
	
	public function  striplashes($item){
		$item=preg_replace("/([\r\n])+/", "", $item);//过滤掉一种奇葩编码,shit!
		$item=str_replace("\\","\\\\",$item);
		return  $item;
	}
	public function steps()
	{
		return $this->hasMany('TcStep')->orderBy('num')->orderBy('created_at','asc');
	}
	
	public function description(){
		
		$arr =is_object($this->column)?$this->column:json_decode('{'.($this->column).'}',true);
		if(!$arr)return null;
		if(array_key_exists('description',$arr)){
			return $arr['description'];
		}else if(array_key_exists('test case description',$arr)){
			return  $arr['test case description'];
		}else if(array_key_exists('test description',$arr)){
			return  $arr['test case description'];
		}else return null;
		
	}
	
	public function  dynamic_col($key){
		
		$arr =is_object($this->column)?$this->column:json_decode('{'.($this->column).'}',true);
		if(!$arr)return null;
		if(array_key_exists($key,$arr)){
			return $arr[$key];
		}else{
			return [];
		}
		
	}

	public function sources()
	{
		//.var_dump($this->column);exit;
		$arr =is_object($this->column)?$this->column:json_decode('{'.($this->column).'}');
		if(!$arr)return [];
		property_exists($arr,'source')?preg_match_all('/\[.*?\]/i',$arr->source,$matches):($matches[0]=[]);
		return $matches[0];
	}
	public function dests()
	{
		$tcs = [];
		$srcs = $this->version->document->dest();
		$this->column=json_decode('{'.$this->column.'}');
		if(!$this->column||!property_exists($this->column,'source'))return [];
		preg_match_all('/\[.*?\]/i',$this->column->source,$matches);
		foreach($srcs as $src){
			switch($src->type){
				case 'tc':
					$tmp = Tc::where('version_id', '=', $src->latest_version()->id)->whereIn('tag',$matches[0])->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v)
					{
						!in_array($v,$tcs)?$tcs[]=$v:null;
					};break;
				case  'rs':
					$tmp = Rs::where('version_id', '=', $src->latest_version()->id)->whereIn('tag',$matches[0])->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v)
					{
						!in_array($v,$tcs)?$tcs[]=$v:null;
					};break;
				default:
			}//switch
		};//foreach
		return $tcs;
		//return $this->belongsToMany('Tc', 'tc_source', 'source_id', 'tc_id');
	}
	public function srcs()
	{
		$result = [];
		//if(!$this->version||!$this->version->document)return $result;
		$srcs = $this->version->document->src();
		foreach($srcs as $src){
			switch($src->type){
				case 'tc':
					$tmp = Tc::where('version_id','=',$src->latest_version()->id)->where('column', 'like', '%"source":%'.$this->tag.'%')->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v){
						if(!in_array($v,$result))$result[]=$v;
					};break;
				case  'rs':
					$tmp = Rs::where('version_id','=',$src->latest_version()->id)->where('column', 'like', '%"source":%'.$this->tag.'%')->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
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
