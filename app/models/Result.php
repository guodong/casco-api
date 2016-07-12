<?php
class Result extends BaseModel {

	protected $table = 'result';
	protected $fillable = array('tc_id', 'tc_id', 'testjob_id',  'cr', 'comment', 'created_at', 'updated_at');
	
	public function tc()
	{
	    return $this->belongsTo('Tc', 'tc_id');
	}
	
	public function steps()
	{
	    return $this->hasMany('ResultStep');
	}
	
	public function  testjob()
	{
	    return $this->belongsTo('Testjob', 'testjob_id');
	}
}
