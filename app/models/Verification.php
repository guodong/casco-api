<?php
class Verification extends BaseModel {

	protected $table = 'verification';
	protected $fillable = array('version','author','description', 'project_id', 'child_id', 'child_version_id', 'parent_version_id', 'parent_id','status');

	
	public function childVersion()
	{
	    return $this->belongsTo('Version','child_version_id','id');
	}
	
	public function childMatrix(){
		
		
		return $this->hasMany('ChildMatrix')->orderBy('Child Requirement Tag','asc');
		
		
	}
	public function parentMatrix(){
		
		
		return $this->hasMany('ParentMatrix');
		
		
	}
	public function parentVersions()
	{
	    return $this->belongsToMany('Version', 'verification_parent_version', 'verification_id', 'parent_v_id');
	}
	
	
}
