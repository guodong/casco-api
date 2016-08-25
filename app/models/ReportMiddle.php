<?php
class ReportMiddle extends BaseModel {

	protected $table = 'report_middle';
	protected $fillable = array('id','child_id','Child Requirement Tag','result_id','comment','p_id');
	

	public function  child(){
		
		return $this->belongsTo('Tc','child_id');
	}
	public function  result(){
		
		return $this->belongsTo('Result','result_id');
	}
	public function   reportcover() {
		
		return $this->belongsTo('ReportCover','p_id');
	}
	
}
