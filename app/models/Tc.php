<?php
class Tc extends BaseModel {

	protected $table = 'tc';
	protected $fillable = array('tag', 'document_id', 'description', 'testmethod_id', 'pre_condition', 'result');

	public function steps()
	{
		return $this->hasMany('TcStep')->orderBy('num');
	}
	
	public function sources()
	{
	    return $this->belongsToMany('Tag', 'tc_source', 'tc_id', 'source_id');
	}
	
	public function testmethod()
	{
	    return $this->belongsTo('Testmethod');
	}
}
