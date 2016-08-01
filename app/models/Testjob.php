<?php
class Testjob extends BaseModel {
	
	use SoftDeletingTrait;
	protected $table = 'testjob';
	protected $fillable = array('name', 'project_id', 'build_id', 'vat_build_id', 'status','created_at','updated_at');
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
		
		$tests=Testjob::where('vat_build_id',$this->vat_build_id)->where('status',1)->where('created_at','<=',$this->created_at)->get();
		$tmp=[];$ans=[];
		foreach($tests as $v){
			foreach($v->results as $data){
				//if(!property_exists($data,'tc')||!property_exists($data->tc,'tag'))continue;
				if(!in_array($data->tc->tag,$tmp)){
					$tmp[$data->tc->tag]=$data;
				}else{
					if($tmp[$data->tc->tag]['created_at']<$data['created_at']){
					$tmp[$data->tc->tag]=$data;
					}
				}
			}//foreach
		}//foreach
		return $tmp;
	}

	public function results()
	{
	    return $this->hasMany('Result', 'testjob_id');
	}
}
