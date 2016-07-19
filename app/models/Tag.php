<?php
class Tag extends BaseModel {

	protected $table = 'rs';
	protected $fillable = array('tag','column','vat_json','created_at','updated_at','version_id');
	
	
	public function  striplashes($item){

		$item=preg_replace("/([\r\n])+/", "", $item);//过滤掉一种奇葩编码,shit!
		$item=str_replace('\\','\\\\',$item);
		return  $item;
	}

	public function dests()
	{
		$this->column=$this["original"]["column"];
		$this->column=$this->striplashes($this->column);
	}
	public function srcs()
	{
		$this->column=$this["original"]["column"];
		$this->column=$this->striplashes($this->column);

	}

	

	public function sources()
	{
		$this->column=$this["original"]["column"];
		$this->column=$this->striplashes($this->column);
	}
	
	
		
	public function description(){
		$this->column=$this["original"]["column"];
		$this->column=$this->striplashes($this->column);		
	}
	public function  column_text(){
		$this->column=$this["original"]["column"];
		$this->column=$this->striplashes($this->column);
	}

}
