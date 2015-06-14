<?php
class Tag extends BaseModel {

	protected $table = 'tag';
	//protected $fillable = array('tag', 'document_id', 'description', 'test_method', 'pre_condition', 'result');


	public function document()
	{
	    return $this->belongsTo('Document');
	}
	
}
