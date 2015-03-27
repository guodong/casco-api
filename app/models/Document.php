<?php
class Document extends BaseModel {


	protected $table = 'document';
	protected $fillable = array('name', 'project_id', 'type', 'graph', 'regex', 'filename');

	
	public function tcs()
	{
	    return $this->hasMany('Tc');
	}

	public function rss()
	{
	    return $this->hasMany('Rs');
	}
	
	public function srcs()
	{
	    return $this->belongsToMany('Document', 'relation', 'dest', 'src');
	}
	
	public function dests()
	{
	    return $this->belongsToMany('Document', 'relation', 'src', 'dest');
	}
}
