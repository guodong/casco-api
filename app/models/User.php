<?php
class User extends BaseModel {

	protected $table = 'user';

	protected $fillable = array('password', 'account', 'realname', 'jobnumber','role_id','islock');

	public function projects()
	{ //获取该员工的工程项目
	    return $this->belongsToMany('Project','Project_User','user_id','project_id');
	}
}
