<?php
class Report extends BaseModel {

	protected $table = 'report';
	protected $fillable = array('name','author','description','child_id','test_id','project_id','created_at','updated_at');
    
	public function verify()
	{
	    return $this->hasMany('ReportVerify', 'report_id');
	}
	
	public function  project(){
		return  $this->belongsTo('Project','project_id');
	}
	
	public  function  testjob(){
		
		return  $this->belongsTo('Testjob','test_id');
	}
	

}

?>