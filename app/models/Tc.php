<?php
class Tc extends BaseModel {

	protected $table = 'tc';
	protected $fillable = array('tag', 'version_id', 'description', 'testmethod_id', 'pre_condition', 'result','source_json');

	public function steps()
	{
		return $this->hasMany('TcStep')->orderBy('num');
	}
	
	public function sources() 
	{
	    $arr = json_decode($this->source_json);
	    return $arr?$arr:[];
	    return $this->belongsToMany('Tag', 'tc_source', 'tc_id', 'source_id');
	}
	
	
	
	public function results()
	{
	    return $this->hasMany('Result');
	}
}
