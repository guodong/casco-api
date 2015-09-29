<?php
class User extends BaseModel {

	protected $table = 'user';

	protected $fillable = array('password', 'account', 'realname', 'jobnumber','role_id','islock');

	public function projects()
	{ //获取该员工的工程项目
	    return $this->belongsToMany('Project');
	}
	public function projects_user(){
		//根据user_id获取project_user里面的记录
		return  $this->hasMany('Project_User','user_id','id');
		
	}
}
