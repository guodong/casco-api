<?php
class ReportCover extends BaseModel {

	protected $table = 'report_cover_status';
	protected $fillable = array('id','parent_id','Parent Requirement Tag','parent_type','report_id','vats','created_at','updated_at');
	
	public function  report()
	{
	    return $this->belongsTo('Report', 'report_id');
	}
	public function  parents()
	{
		return $this->belongsTo('Rs','parent_id');
	}
	public function  middles(){
		
		return $this->hasMany('ReportMiddle','p_id','id');
	}
	
}
