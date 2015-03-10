<?php
class Project extends BaseModel {

	protected $table = 'project';
	protected $fillable = array('name', 'description', 'graph');
	
	public function documents()
	{
	    return $this->hasMany("Document");
	}
	
	public function vatstrs()
	{
	    return $this->hasMany("Vatstr");
	}
	
	public function participants()
	{
	    return $this->belongsToMany('User', 'project_user');
	}
	
}
