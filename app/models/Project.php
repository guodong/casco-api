<?php
class Project extends Eloquent {

	protected $table = 'project';
	protected $fillable = array('name', 'description', 'graph');
	
	public function documents()
	{
	    return $this->hasMany("Document");
	}
}
