<?php
class TcStep extends BaseModel {

	protected $table = 'tc_step';
	protected $fillable = array('tc_id', 'num', 'actions', 'expected result');
    public  $id=false;
    
	public function tc()
	{
	return $this->belongsTo('Tc');
	}


}
