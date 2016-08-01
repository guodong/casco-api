<?php
class TestjobRs extends BaseModel {
	
	use SoftDeletingTrait;
	protected $table = 'testjob_rs_version';
	protected $fillable = array('testjob_id','rs_version_id');
	protected $dates=['deleted_at'];
    
	public function testjob()
	{
	    return $this->belongsTo('Testjob', 'testjob_id','id');
	}
	
}

?>