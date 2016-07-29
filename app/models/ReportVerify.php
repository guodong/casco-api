<?php
class ReportVerify extends BaseModel {

	protected $table = 'report_verify';
	protected $fillable = array('rs_id','tc_id','doc_id','result','report_id','comment','created_at','updated_at');
	
	public function  report()
	{
	    return $this->belongsTo('report', 'report_id');
	}
	
	public function  version(){
		
		return $this->belongsTo('Version','doc_id');
	}
	
	
}
