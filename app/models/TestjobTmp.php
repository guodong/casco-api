<?php
class TestjobTmp extends BaseModel {

	protected $table = 'testjob_tmp';
	protected $fillable = array('name', 'project_id','created_at','updated_at');

	public function project(){    
	return $this->belongsTo('Project');
	}
	
	

}
?>