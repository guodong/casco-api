<?php
class Rs extends BaseModel {

	protected $table = 'rs';
	protected $fillable = array();
    
	public function tcs()
	{
	    return $this->belongsToMany('Tc', 'tc_source', 'source_id', 'tc_id');
	}
	
	public function vat()
	{
	    return $this->belongsToMany('Tag', 'rs_vat', 'rs_id', 'vat_id');
	}
	
	public function vatstr()
	{
	    return $this->belongsTo('Vatstr');
	}

}
