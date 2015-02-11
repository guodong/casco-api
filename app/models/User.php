<?php
class User extends BaseModel {

	protected $table = 'user';

	protected $fillable = array('password', 'account', 'realname', 'jobnumber');

}
