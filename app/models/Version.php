<?php
class Version extends BaseModel {


	protected $table = 'version';
	protected $fillable = array('name', 'document_id', 'filename');

	public function tcs()
	{
	    return $this->hasMany('Tc');
	}
	
	public function rss()
	{
	    return $this->hasMany('Rs');
	}
	
	public function document()
	{
	    return $this->belongsTo('Document');
	}
}
