<?php
class ParentMatrix extends BaseModel {
	
	protected $table = 'parent_matrix';
	protected $fillable = array('parent_id','Parent Requirement Tag','parent_type','child_id','Child Requirement Tag','child_type','justification'
	,'Completeness','No Compliance Description','Defect Type','Verif_Assesst','Verif Assest justifiaction','CR','Comment','column','parent_v_id','verification_id','updated_at','created_at');
	
	public function verification(){
		return  $this->belongsTo('Verification');
	}
	
	public function version(){
		return $this->belongsTo('Version','parent_v_id','id');
	}
	
}
?>