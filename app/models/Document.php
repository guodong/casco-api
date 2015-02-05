<?php
class Document extends BaseModel {


	protected $table = 'document';
	protected $fillable = array('name', 'project_id', 'type', 'graph');

	
	public function tcs()
	{
	    return $this->hasMany('Tc');
	}

	public function rss()
	{
	    return $this->hasMany('Rs');
	}
}
