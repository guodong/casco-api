<?php
class VatRs extends BaseModel{
    
    protected $table = 'vat_rs_version';
    protected $fillable = array('vat_build_id','rs_version_id');
    
    public function vatBuild() {
        return $this->belongsTo('VatBuild','vat_build_id');
    }
    
}
