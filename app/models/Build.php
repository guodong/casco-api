<?php
class Build extends BaseModel {

	protected $table = 'build';
	protected $fillable = array('name', 'project_id');

	public function tcs()
	{
		return $this->belongsToMany('Tc')->withPivot('result');
	}
	
}
