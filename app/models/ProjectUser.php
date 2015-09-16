<?php
class ProjectUser extends BaseModel {

	protected $table = 'project_user';

	protected $fillable = array('project_id','user_id','doc_noedit');

	
}
