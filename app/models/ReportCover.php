<?php
class ReportCover extends BaseModel {

	protected $table = 'report_cover_status';
	protected $fillable = array('parent_id','Parent Requirement Tag','parent_type','child_id','Child Requirement Tag','child_type','result_id','comment','column','report_id','created_at','updated_at');
	
	public function  report()
	{
	    return $this->belongsTo('report', 'report_id');
	}
	public function  parents()
	{
		return $this->belongsTo('Rs','parent_id');
	}
	
	
}
