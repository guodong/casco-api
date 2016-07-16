<?php
class TestjobRs extends BaseModel {

	protected $table = 'testjob_rs_version';
	protected $fillable = array('testjob_id','rs_version_id');
	
	public function testjob()
	{
	    return $this->belongsTo('Testjob', 'testjob_id','id');
	}
	
}

?>