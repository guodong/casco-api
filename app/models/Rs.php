<?php
class Rs extends BaseModel {

	protected $table = 'rs';
	protected $fillable = array();
    
	public function tcs()
	{
	    return $this->belongsToMany('Tc', 'tc_source', 'source_id', 'tc_id');
	}
	
	//V模型中纵向引用该rs的rs
	public function rss()
	{
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
	    return $this->belongsToMany('Tag', 'rs_source', 'rs_id', 'source_id');
	}
	
}
