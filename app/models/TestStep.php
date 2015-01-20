<?php
class TestStep extends Eloquent {


	protected $table = 'test_step';

	public function tc()
	{
	return $this->belongsTo('Tc');
	}


}
