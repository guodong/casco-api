<?php
class Testjob extends BaseModel {
	
	use SoftDeletingTrait;
	protected $table = 'testjob';
	protected $fillable = array('name', 'project_id', 'build_id', 'vat_build_id', 'user_id','status','created_at','updated_at');
	protected $dates=['deleted_at'];
    
	public function build()
	{
	    return $this->belongsTo('Build');
	}
	public function  vatbuild()
	{
	    return $this->belongsTo('VatBuild', 'vat_build_id');
	}
	
	public function  rencents(){
		
		$tests=Testjob::where('vat_build_id',$this->vat_build_id)->where('created_at','<=',$this->created_at)->get();
		$tmp=[];$ans=[];$tcs=[];$this->vatbuild&&($tcs=$this->vatbuild->tcVersion->tcs);
		foreach($tests as $v){
			foreach($v->results as $data){
				//if(!property_exists($data,'tc')||!property_exists($data->tc,'tag'))continue;
				if(!array_key_exists($data->tc->tag,$tmp)){
					$tmp[$data->tc->tag]=$data;
				}else{
					if($tmp[$data->tc->tag]->updated_at<$data->updated_at){
					$tmp[$data->tc->tag]=$data;
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

	public function results()
	{
	    return $this->hasMany('Result', 'testjob_id');
	}
	
	public function user(){
	    return $this->belongsTo('User','user_id');
	}
}
