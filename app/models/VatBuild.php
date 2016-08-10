<?php
class VatBuild extends BaseModel{
    
	use SoftDeletingTrait;
    protected $table = 'vat_build';
    protected $fillable = array('name','description','project_id','tc_version_id','created_at','updated_at');
    protected $dates=['deleted_at'];
    
    public function project(){
        return $this->belongsTo('Project','project_id');
    }
    
    public function tcVersion(){
        return $this->belongsTo('Version','tc_version_id');
    }
    
    public function rsVersions(){
        return $this->belongsToMany('Version','vat_rs_version','vat_build_id','rs_version_id');
    }
    
    public function  directDests(){
    	$data=[];
    	$doc=$this->tcVersion->document->dest();
    	foreach($doc as $d){
    		foreach($this->rsVersions as $rs_ver){
    			//var_dump(in_array($rs_ver,(array)$d->versions));
    			($rs_ver->document->id==$d->id)&&$data=array_merge($data,$rs_ver->rss->toArray());
    		}
    	}
    	return $data;
    	
    }
    
    
    public function vatRss() {
        return $this->hasMany('VatRs','vat_build_id');
    }

    
}