<?php
class ChildMatrix extends BaseModel {
	
	protected $table = 'child_matrix';
	protected $fillable = array('Child Requirement Tag','Child Requirement Text','Parent Requirement Tag','Parent Requirement Text','Traceability'
	,'No compliance description','Already described in  completeness','Verif. Assessment','Verif. opinion justification','CR','Comment','column','verification_id','updated_at','created_at');
	
	
	public function verification(){
		return  $this->belongsTo('Verification');
	}
	
	
	
	
}
?>