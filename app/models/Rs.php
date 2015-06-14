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
	           $doc = $src->latest_version();
	           if($doc){
	               foreach($doc->tcs as $tc){
	                   if(strstr($tc->source_json, $this->tag)){
    	                   $tcs[] = $tc;
	                   }
	               };
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
	            $doc = $src->latest_version();
	            if($doc){
	                foreach($doc->rss as $rs){
	                    if(strstr($rs->source_json, $this->tag)){
	                        $rss[] = $rs;
	                    }
	                };
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
