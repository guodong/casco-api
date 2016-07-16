<?php
class Rs extends Tag {

	protected $table = 'rs';
	protected $fillable = array('tag','column','vat_json','created_at','updated_at','version_id');
	
	
	
	public function  striplashes($item){

		$item=preg_replace("/([\r\n])+/", "", $item);//过滤掉一种奇葩编码,shit!
		$item=str_replace('\\','\\\\',$item);
		return  $item;
	}
	public function  column(){
		if($this->column){
		return (array)json_decode('{'.$this->column.'}',true);
		}else if(array_key_exists('column',$this["original"])){
		return (array)json_decode('{'.$this->striplashes($this["original"]["column"]).'}',true);
		}else return [];
	}

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
		$tcs = [];parent::dests();
		$srcs = $this->version->document->dest();
		$this->column=json_decode('{'.$this->column.'}');
		if(!$this->column||!property_exists($this->column,'source'))return [];
		preg_match_all('/\[.*?\]/i',preg_replace('/\s/','',$this->column->source),$matches);
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
		$this->tag=preg_replace('/[\[\]]/','',$this->tag);
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
		$arr =is_object($this->column)?$this->column:json_decode('{'.($this->column).'}');
		if(!$arr)return [];
		property_exists($arr,'source')?preg_match_all('/\[.*?\]/i',preg_replace('/\s/','',($arr->source)),$matches):($matches[0]=[]);
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
	
	public function description(){
		
		parent::description();
		$arr =is_object($this->column)?$this->column:json_decode('{'.($this->column).'}',true);
		if(!$arr)return null;
		if(array_key_exists('description',$arr)){
			return $arr['description'];
		}else if(array_key_exists('test case description',$arr)){
			return  $arr['test case description'];
		}else return null;
		
	}
	public function  column_text(){
		parent::column_text();
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
