<?php
class Report extends BaseModel {

	protected $table = 'report';
	protected $fillable = array('version','author','description','doc_id','project_id','created_at','updated_at');
    
	public function verify()
	{
	    return $this->hasMany('ReportVerify', 'report_id');
	}
	
	public function  project(){
		
		return  $this->belongsTo('Project','project_id');
	}
	
	public function  document(){
		
		return $this->belongsTo('Document','doc_id');
	}
	public function  covers(){
	
	    return  $this->hasMany('ReportCover','report_id');
	}
	
	public function  results(){
		
		return  $this->hasMany('ReportResult','report_id');
	}
	
	public  function  testjob($tc_id){
		 //归并所有的Result
		/*$data=[];
        $testjob=Testjob::where('project_id','=',$this->project_id)->where('status',1)
		->orWhere(function($query)use($tc_id){$query->tcVersion->documnet->id=$tc_id})->orderBy('created_at','desc')->get();
		return $testjob;
*/
	}
	

}

?>