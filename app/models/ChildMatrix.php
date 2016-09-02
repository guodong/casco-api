<?php
class ChildMatrix extends BaseModel {
	
	protected $table = 'child_matrix';
	protected $fillable = array('child_id','Child Requirement Tag','child_type','parent_id','Parent Requirement Tag','parent_type','Traceability'
	,'No compliance description','Already described in completeness','Verif_Assessment','Verif. opinion justification','CR','Comment','column','verification_id','updated_at','created_at');

	public function verification(){
		return  $this->belongsTo('Verification');
	}
	
	
	
}
?>