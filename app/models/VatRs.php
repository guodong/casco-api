<?php
class VatRs extends BaseModel{
    
	use SoftDeletingTrait;
    protected $table = 'vat_rs_version';
    protected $fillable = array('vat_build_id','rs_version_id','created_at','updated_at');
    protected $dates=['deleted_at'];
    
    public function vatBuild() {
        return $this->belongsTo('VatBuild','vat_build_id');
    }
    
}
