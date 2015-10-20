<?php
class ProjectUser extends BaseModel {

	protected $table = 'project_user';
    public $timestamps = true;//烦人的多余字段
    public  $id=false;
	protected $fillable = array('project_id','user_id','doc_edit');
    protected $guarded = array('id', 'updated_at','created_at');
	

	
}
?>