<?php
class Document extends BaseModel {


	protected $table = 'document';
	protected $fillable = array('name', 'project_id', 'type', 'fid','graph', 'regex', 'filename');

	public function latest_version()
	{
	    $vss = $this->versions()->orderBy('updated_at', 'desc')->first();
	    if ($vss){
	        return $vss;
	    }
	    return null;
	}
	
	public function tcs()
	{
	    return $this->hasMany('Tc');
	}
     public function project()
        {
            return $this->belongsTo('Project');
        }

	public function rss()//这个不是已经没用了么?
	{
	    return $this->hasMany('Rs');
	}
	
	public function srcs()
	{
	    return $this->belongsToMany('Document', 'relation', 'dest', 'src')->groupby('src');
	}
	
	public function dests()
	{
	    return $this->belongsToMany('Document', 'relation', 'src', 'dest');
	}
	public function subs(){
		
		return $this->hasMany('Document','fid','id');;
	}
	public function versions()
	{
	    return $this->hasMany('Version');
	}
}
