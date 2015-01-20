<?php
class Rs extends Eloquent {


	protected $table = 'rs';
    
	public function tcs()
	{
	    return $this->belongsToMany('Tc', 'rs_tc');
	}

}
