<?php
class Tc extends Eloquent {


	protected $table = 'tc';

	public function steps()
	{
		return $this->hasMany('TestStep');
	}

	public function rss()
	{
	    return $this->belongsToMany('Rs', 'rs_tc');
	}
}
