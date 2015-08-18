<?php
class ResultStep extends BaseModel {

	protected $table = 'result_step';
	protected $fillable = array('result_id', 'step_id', 'result', 'comment');

	public function step()
	{
	   return $this->belonsTo('TcStep', 'step_id');
	}


}
