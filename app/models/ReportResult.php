<?php
class ReportResult extends BaseModel {

	protected $table = 'report_result';
	protected $fillable = array('id','result_id','tc_id','report_id','created_at','updated_at');
	
	public function  report()
	{
	    return $this->belongsTo('report', 'report_id');
	}
	
	public function  result(){
		
		return $this->belongsTo('Result','result_id');
	}
	
	public function   tc() {
		
		return $this->belongsTo('Tc','tc_id');
	}
	
}
