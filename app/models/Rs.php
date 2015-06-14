<?php
class Rs extends BaseModel {

	protected $table = 'rs';
	protected $fillable = array();
    
	public function tcs()
	{
	    $tcs = [];
	    $srcs = $this->version->document->srcs;
	    foreach($srcs as $src){
	       if($src->type == 'tc'){
	            $version = $src->latest_version();
	            if($version){
	                $tcs = Tc::where('version_id', '=', $version->id)->where('source_json', 'like', '%'.$this->tag.'%')->get();
	            }
	       } 
	    };
	    return $tcs;
	    return $this->belongsToMany('Tc', 'tc_source', 'source_id', 'tc_id');
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
	                $rss = Rs::where('version_id', '=', $version->id)->where('source_json', 'like', '%'.$this->tag.'%')->get();
	            }
	        }
	    };
	    return $rss;
	    return $this->belongsToMany('Rs', 'rs_source', 'source_id', 'rs_id');
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
	    $arr = json_decode($this->source_json);
	    return $arr?$arr:[];
	    return $this->belongsToMany('Tag', 'rs_source', 'rs_id', 'source_id');
	}
	
	public function version()
	{
	    return $this->belongsTo('version');
	}
	
}
