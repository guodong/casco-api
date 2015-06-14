<?php
class User extends BaseModel {

	protected $table = 'user';

	protected $fillable = array('password', 'account', 'realname', 'jobnumber','role');

	public function projects()
	{
	    return $this->belongsToMany('Project');
	}
}
