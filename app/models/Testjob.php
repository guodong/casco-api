<?php
class Testjob extends BaseModel {

	protected $table = 'testjob';
	protected $fillable = array('name', 'project_id', 'build_id', 'tc_version_id', 'rs_version_id');

	public function build()
	{
	    return $this->belongsTo('Build');
	}
	
	public function tcVersion()
	{
	    return $this->belongsTo('Version', 'tc_version_id');
	}
	
	public function rsVersion()
	{
	    return $this->belongsTo('Version', 'rs_version_id');
	}
	
	public function results()
	{
	    return $this->hasMany('Result', 'testjob_id');
	}
}
