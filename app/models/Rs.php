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
	                    if($v->column&&property_exists($v->column,'source')&&in_array($this->tag,explode(',',$v->column->source))){$tcs[]=array('id' => $v->id,'tag' => $v->tag);}
	                	
	                }
	            }
	       } 
	    };
	    return $tcs;
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
	                    if($v->column&&property_exists($v->column,'source')&&in_array($this->tag,explode(',',$v->column->source))){$rss[]=array('id' => $v->id,'tag' => $v->tag);}
	                	
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
	    $arr = json_decode('{'.$this->column.'}');
	    if(!$arr)return [];//var_dump( property_exists($arr,'source')?explode(',',str_replace(array("\r\n", "\r", "\n"," "), "", $arr->source)):[]);
	    return property_exists($arr,'source')?explode(',',str_replace(array("\r\n", "\r", "\n"," "), "", $arr->source)):[];
	    return $this->belongsToMany('Tag', 'tc_source', 'tc_id', 'source_id');
	  //  return $this->belongsToMany('Tag', 'rs_source', 'rs_id', 'source_id');
	}
	
	public function version()
	{
	    return $this->belongsTo('Version');
	}
	
}
