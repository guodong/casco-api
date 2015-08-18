<?php
class Result extends BaseModel {

	protected $table = 'result';
	protected $fillable = array('tc_id', 'tc_id', 'testjob_id', 'result', 'cr', 'comment', 'begin_at', 'end_at');
	
	public function tc()
	{
	    return $this->belongsTo('Tc', 'tc_id');
	}
	
	public function steps()
	{
	    return $this->hasMany('ResultStep');
	}
}
