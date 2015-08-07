<?php
class Vatstr extends BaseModel {

	protected $table = 'vatstr';
	protected $fillable = array('name');
	
	public function project()
	{
	    return $this->belongsTo('Project');
	}
	
}
