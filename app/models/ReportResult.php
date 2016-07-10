<?php
class ReportResult extends BaseModel {

	protected $table = 'report_result';
	protected $fillable = array('id','result_id','report_id','created_at','updated_at');
	
	public function  report()
	{
	    return $this->belongsTo('report', 'report_id');
	}
	public function  result()
	{
	    return $this->belongsTo('result', 'result_id');
	}
	
	
}
