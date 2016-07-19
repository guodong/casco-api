<?php
class VatView extends BaseModel{
    protected $table = 'VatView';
    protected $fillable = array('name','description','project_id','tc_version_id','rs_version_id_json','created_at','updated_at');
    
    public function tcVersion(){
        return $this -> belongsTo('Version','tc_version_id');
    }
    
//     public function rsVersion(){
//         return $this -> hasMany
//     }
    
}