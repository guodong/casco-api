<?php
class Rs extends BaseModel {

	protected $table = 'rs';
	protected $fillable = array('tag','column','version_id');

	public function tcs()
	{
		$tcs = [];
		$srcs = $this->version->document->srcs;
		foreach($srcs as $src){
			if($src->type == 'tc'){
				$version = $src->latest_version();
				if($version){
					$tmp = Tc::where('version_id', '=', $version->id)->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v){
						$v->column=json_decode('{'.$v->column.'}');
						if($v->column&&property_exists($v->column,'source')&&in_array($this->tag,explode(',',$v->column->source))){
							!in_array(array('id' => $v->id,'tag' => $v->tag),$tcs)?$tcs[]=array('id' => $v->id,'tag' => $v->tag):'';}

					}
				}
			}
		};
		return $tcs;
		//return $this->belongsToMany('Tc', 'tc_source', 'source_id', 'tc_id');
	}
	public function dests()
	{
		$tcs = [];
		$srcs = $this->version->document->dests;
		$this->column=json_decode('{'.$this->column.'}');
		if(!$this->column||!property_exists($this->column,'source'))return [];
		preg_match_all('/\[.*?\]/i',$this->column->source,$matches);
		//var_dump($matches);
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
		$rss = [];

		$srcs = $this->version->document->srcs;
		foreach($srcs as $src){
			if($src->type == 'rs'){
				$version = $src->latest_version();
				if($version){
					$tmp = Rs::where('version_id', '=', $version->id)->get();//->where('source_json', 'like', '%'.$this->tag.'%')->get();
					foreach ($tmp as $v){
						$v->column=json_decode('{'.$v->column.'}');
						if($v->column&&property_exists($v->column,'source')&&in_array($this->tag,explode(',',$v->column->source)))
						{!in_array(array('id' => $v->id,'tag' => $v->tag),$rss)?$rss[]=array('id' => $v->id,'tag' => $v->tag):'';}

					}
				}
			}
		};

		// var_dump($rss);
		return $rss;
		//return $this->belongsToMany('Rs', 'rs_source', 'source_id', 'rs_id');
	}

	public function vat()
	{
		return $this->belongsToMany('Tag', 'rs_vat', 'rs_id', 'vat_id');
	}

	public function vatstr()
	{
		return $this->belongsTo('Vatstr');
	}

	public function sources()
	{
		//.var_dump($this->column);exit;
		$arr =is_object($this->column)?$this->column:json_decode('{'.($this->column).'}');
		if(!$arr)return [];
	    property_exists($arr,'source')?preg_match_all('/\[.*?\]/i',$arr->source,$matches):($matches[0]=null);
	    return $matches[0];
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
		
		$column=json_decode('{'.$this->column.'}',true);$ans=null;
		foreach((array)$column as  $key=>$value){
			if(strtolower($key)=='description' ||strtolower($key)=='test case description'){
			$ans=$value.'\n'.$ans;}
			else 
			$ans.='#'.$key.':'.$value.'\n';
		}
		return $ans;
		
	}

}
