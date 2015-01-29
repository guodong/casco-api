<?php
class TcStep extends Eloquent {

	protected $table = 'tc_step';
	protected $fillable = array('tc_id', 'num', 'actions', 'expected_result');

	public function tc()
	{
	return $this->belongsTo('Tc');
	}


}
