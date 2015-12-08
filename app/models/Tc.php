<?php
class Tc extends BaseModel {

	protected $table = 'tc';
	protected $fillable = array('tag','column','checklog','robot','version_id', 'description', 'testmethod_id', 'pre_condition', 'result','source_json');

	public function steps()
	{
		return $this->hasMany('TcStep')->orderBy('num');
	}
	
	public function sources() 
	{
	    $arr = json_decode('{'.$this->column.'}');
	    //var_dump($arr);
	    if(!$arr)return [];
	    return property_exists($arr,'source')?explode(',',str_replace(array("\r\n", "\r", "\n"," "), "", $arr->source)):[];
	}

	
	public function results()
	{
	    return $this->hasMany('Result');
	}
}
