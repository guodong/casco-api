<?php
class Testjob extends BaseModel {
	
	use SoftDeletingTrait;
	protected $table = 'testjob';
	protected $fillable = array('name', 'project_id', 'build_id', 'vat_build_id', 'tc_version_id','user_id','status','created_at','updated_at');
	protected $dates=['deleted_at'];
    
	public function build()
	{
	    return $this->belongsTo('Build');
	}
	public function  vatbuild()
	{
	    return $this->belongsTo('VatBuild', 'vat_build_id')->withTrashed();
	}
 	public function tcVersion(){
        return $this->belongsTo('Version','tc_version_id');
    }
	public function  rencents(){
		//而且tc_version也要一致的吧
		$tests=Testjob::where('vat_build_id',$this->vat_build_id)->where('tc_version_id',$this->tc_version_id)->where('created_at','<=',$this->created_at)->get();
		$tmp=[];$ans=[];$tcs=[];$this->vatbuild&&($tcs=$this->tcVersion->tcs);
		foreach($tests as $v){
			//var_dump($v->results);
			foreach((array)json_decode($v->results,true) as $data){
				$tc=Tc::find($data['tc_id']);
				if(!array_key_exists($tc->tag,$tmp)){
					$tmp[$tc->tag]=$data;
				}else{
					if($tmp[$tc->tag]->updated_at<$data['updated_at']){
					$tmp[$tc->tag]=$data;
					}
				}
			}//foreach
		}//foreach
		//最后求交集的部分吧
		foreach($tcs as  $tc){
		if(!array_key_exists($tc->tag,$tmp))
		$tmp[$tc->tag]=array('tc_id'=>$tc->id,'result'=>0,'testjob_id'=>$this->id);
		}
		return $tmp;
	}
	
	 public function  directDests(){
    	$data=[];
    	$doc=$this->tcVersion->document->dest();
    	foreach($doc as $d){
    		foreach($this->vatbuild->rsVersions() as $rs_ver){
    			($rs_ver->document->id==$d->id)&&$data=array_merge($data,$rs_ver->rss->toArray());
    		}
    	}
    	return $data;
    	
    }
	public function results()
	{
	    return $this->hasMany('Result', 'testjob_id');
	}
	
	public function user(){
	    return $this->belongsTo('User','user_id');
	}
}