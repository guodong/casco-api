<?php
class Project extends Eloquent {

	protected $table = 'project';
	protected $fillable = array('name', 'description');
}
