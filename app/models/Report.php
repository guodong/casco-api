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
	
	public function  recursion(){//树的遍历操作算法
		
		$docs=[];$data=[];
		$docker=Document::find($this->doc_id);
		if(!$docker)return [];
		array_push($docs,$docker);
		while($doc=array_shift($docs)){
			//var_dump($doc->name);
			foreach($doc->dest() as $dests){
				if($dests->type=="rs"&&!in_array($dests,$data)){
					array_push($docs,$dests);
					array_push($data,$dests);
				}
			}
		}//while
		
		//var_dump($docker->dest());exit;
		foreach($docker->dest() as $dests){
			array_push($docs,$dests);
		}
		while($doc=array_shift($docs)){
			//var_dump($doc->srcs);
			foreach($doc->src()  as $srcs){
			   if($srcs->type=="rs"&&!in_array($srcs,$data)){
					array_push($docs,$srcs);
					array_push($data,$srcs);
				}
			}
		}//while
		return $data;
			
	}
	
	
	

}

?>