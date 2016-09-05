<?php
class VatDocs extends BaseModel{
    
	use SoftDeletingTrait;
    protected $table = 'vat_doc_version';
    protected $fillable = array('vat_build_id','doc_version_id','doc_type','created_at','updated_at');
    protected $dates=['deleted_at'];
    
    public function vatBuild() {
        return $this->belongsTo('VatBuild','vat_build_id');
    }
    
}
