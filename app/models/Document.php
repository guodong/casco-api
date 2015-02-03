<?php
class Document extends Eloquent {


	protected $table = 'document';
	protected $fillable = array('name', 'project_id', 'type');

	
	public function tcs()
	{
	    return $this->hasMany('Tc');
	}

	public function rss()
	{
	    return $this->hasMany('Rs');
	}
}
