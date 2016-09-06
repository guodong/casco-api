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
    
    public function docVersions(){
        return $this->belongsToMany('Version','vat_doc_version','vat_build_id','doc_version_id');
    }

    public function rsVersions(){
        $rsvers = [];
        $docvers = $this->docVersions();
        foreach ($docvers as $dv){
            $doci = Document::find($dv->document_id);
            if($doci->type == 'rs') $rsvers[]=$dv;
        }
        return $rsvers;
//         return $this->belongsToMany('Version','vat_rs_version','vat_build_id','rs_version_id');
    }
    
   
    
    public function vatRss() {
        return $this->hasMany('VatDocs','vat_build_id');
    }

    
}