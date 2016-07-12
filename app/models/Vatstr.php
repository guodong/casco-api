<?php
class Vatstr extends BaseModel {

	protected $table = 'vatstr';
	protected $fillable = array('name','created_at','updated_at');
	
	public function project()
	{
	    return $this->belongsTo('Project');
	}
	
}
