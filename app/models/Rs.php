<?php
class Rs extends Eloquent {

	protected $table = 'rs';
	protected $fillable = array('vat');
    
	public function tcs()
	{
	    return $this->belongsToMany('Tc', 'tc_source', 'source_id', 'tc_id');
	}

}
