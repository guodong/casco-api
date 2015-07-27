<?php
class Result extends BaseModel {

	protected $table = 'result';
	protected $fillable = array('tc_id', 'tc_id', 'testjob_id', 'result', 'cr', 'comment');
	
	public function tc()
	{
	    return $this->belongsTo('Tc', 'tc_id');
	}
}
