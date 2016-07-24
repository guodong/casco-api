<?php
class Testjob extends BaseModel {

	protected $table = 'testjob';
	protected $fillable = array('name', 'project_id', 'build_id', 'tc_version_id', 'rs_version_id', 'status');

	public function build()
	{
	    return $this->belongsTo('Build');
	}
	
	public function tcVersion()
	{
	    return $this->belongsTo('Version', 'tc_version_id');
	}
	
	public function rsRelations()
	{
		return $this->hasMany('TestjobRs','id','testjob_id');
	}
	
	public function rsVersions()
	{
	    return $this->belongsToMany('Version', 'testjob_rs_version', 'testjob_id', 'rs_version_id');
	}
	
	public function results()
	{
	    return $this->hasMany('Result', 'testjob_id');
	}
}
