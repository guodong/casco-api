<?php
class Tc extends BaseModel {

	protected $table = 'tc';
	protected $fillable = array('tag', 'document_id', 'description', 'test_method', 'pre_condition', 'result');

	public function steps()
	{
		return $this->hasMany('TcStep');
	}
	
	public function sources()
	{
	    return $this->belongsToMany('Tag', 'tc_source', 'tc_id', 'source_id');
	}
}
