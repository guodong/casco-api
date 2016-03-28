<?php
class Tc extends BaseModel {

	protected $table = 'tc';
	protected $fillable = array('tag','column','checklog','robot','version_id', 'description', 'testmethod_id', 'pre_condition', 'result','source_json');

	public function steps()
	{
		return $this->hasMany('TcStep')->orderBy('num');
	}

	public function sources()
	{
		//.var_dump($this->column);exit;
		$arr =is_object($this->column)?$this->column:json_decode('{'.($this->column).'}');
		if(!$arr)return [];
		property_exists($arr,'source')?preg_match_all('/\[.*?\]/i',$arr->source,$matches):($matches[0]=null);
		return $matches[0];
	}
	public function dests()
	{
		$tcs = [];
		$srcs = $this->version->document->dests;
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
		$srcs = $this->version->document->srcs;
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
