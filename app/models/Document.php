<?php
class Document extends BaseModel {


	protected $table = 'document';
	protected $fillable = array('name', 'project_id', 'type', 'graph', 'regex', 'filename');

	public function latest_version()
	{
	    $vss = $this->versions()->orderBy('created_at', 'desc')->get();
	    if (count($vss)){
	        return $vss[0];
	    }
	    return null;
	}
	
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
	
	public function versions()
	{
	    return $this->hasMany('Version');
	}
}
