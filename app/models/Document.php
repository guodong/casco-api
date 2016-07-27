<?php
class Document extends BaseModel {


	protected $table = 'document';
	protected $fillable = array('id','name', 'project_id', 'type', 'fid','graph', 'regex', 'filename');

	public function latest_version()
	{
		//最新视为最后一次导入而不是修改!
		$vss = $this->versions()->orderBy('created_at', 'desc')->first();
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
		return $this->belongsToMany('Document', 'relation', 'dest', 'src');
	}

	public function src(){

		$result=Document::whereExists(function ($query) {
			$query->select(DB::raw(1))
			->from('relation')
			->whereRaw('dest=\''.$this->id.'\' and src=document.id');
		})
		->distinct()->get();
		return  $result;

	}

	public function dests()//记得去重哦
	{
		return $this->belongsToMany('Document', 'relation', 'src', 'dest');
	}

	public function dest()//记得去重哦
	{

		$result=Document::whereExists(function ($query) {
			$query->select(DB::raw(1))
			->from('relation')
			->whereRaw('src=\''.$this->id.'\' and dest=document.id');
		})
		->distinct()->get();
		return  $result;
		// $testjob=Document
		//->orWhere(function($query)use($tc_id){$query->tcVersion->documnet->id=$tc_id})->orderBy('created_at','desc')->get();

	}

	public function subs(){

		return $this->hasMany('Document','fid','id');;
	}
	public function versions()
	{
		return $this->hasMany('Version')->orderBy('created_at','desc');
	}
}
