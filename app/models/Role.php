<?php
class Role extends BaseModel{
    
    protected $table = 'role';
    protected $fillable = array('name','description');
    
    //一对多
    public function users(){
        return $this->hasMany(User);
    }
}