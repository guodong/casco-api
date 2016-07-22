<?php
class Project extends BaseModel {

	protected $table = 'project';
	protected $fillable = array('name', 'description', 'graph');
	
	public function documents()
	{
	    return $this->hasMany("Document");
	}
	
	public function vatbuilds(){
	    return $this->hasMany('VatBuild','project_id','id')->orderBy('created_at','desc');
	}
	
	public function testjobtmps()
	{
	    return $this->hasMany("TestjobTmp");
	}
	
	public function vatstrs()
	{
	    return $this->hasMany("Vatstr")->orderBy('name');
	}
	
	public function participants()
	{
	    return $this->belongsToMany('User', 'project_user','project_id','user_id');

	}
	public function testjobs()
	{
	    return $this->hasMany('Testjob')->orderBy('created_at', 'desc');
	}
	public function verifications()
	{
	    return $this->hasMany('Verification')->orderBy('created_at', 'desc');
	}
	
}
