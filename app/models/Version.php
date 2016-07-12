<?php
class Version extends BaseModel {


	protected $table = 'version';
	protected $fillable = array('name', 'document_id', 'headers');

	public function tcs()
	{
	    return $this->hasMany('Tc')->orderBy('tag')->distinct();
	}
	public function rss()
	{
	    return $this->hasMany('Rs')->orderBy('tag')->distinct();
	}
	public function items(){
		
		return array_merge($this->tcs(),$this->rss());
	}
	public function document()
	{
	    return $this->belongsTo('Document');
	}
	public function parent_item($parent_vids){
		
	  $type=$this->document->type;
	  $dests = $this->document->dests;
	  $child_item=[];$parent_item=array();
	   //$parent_vid是数组,而且与$dest是对应的我去!只用version_id就足够了吧
	  foreach($parent_vids as $parent_vid){
	  	//var_dump($parent_vid);
	  	if(Tc::where('version_id', '=', $parent_vid)->get()->toArray()!=null){	
	    array_push($parent_item,Tc::where('version_id', '=', $parent_vid)->get()->toArray());
	  	}else{array_push($parent_item,Rs::where('version_id', '=', $parent_vid)->get()->toArray());}
	   
	  }//foreach
	    return  $parent_item;
	   	
	}
	
	public function  child_item($child_vid){
		
	  $type=$this->document()->type;
	  $srcs = $this->document()->srcs;
	  $child_item=array();$parent_item=array();
	  foreach($srcs as $src){
	  switch($src->type){
	  	case 'tc':
	  	$child_item[]=Rs::where('version_id', '=', $child_vid)->get();break;
	  	case 'rs':
	  	$child_item[]=Tc::where('version_id', '=', $child_vid)->get();break;
	  	default:
	  }
	  }
	    return  $child_item;
			
	}
	
	
	public function  rs_parent_header(){
		
	   $column=explode(',',$this->header);
	   $type=$this->document()->type;
	   array_map('filter',$column);
	   function filter($item){
	   	
	   	
	   
	   }
		
		
	}
	
	
}
