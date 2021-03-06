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
	
	    return  $this->hasMany('ReportCover','report_id')->orderBy('Parent Requirement Tag','asc');
	}
	
	public function  testjob(){
		
		return $this->belongsTo('Testjob');
		
	}
	public function  results(){
		
		return  $this->hasMany('ReportResult','report_id');
	}
	
	public function  get_results(){
		//必须要合并好吧
		$tests=$this->results;$datas=[];
			foreach($tests as $res){
				$result=Result::find($res->result_id);
				$tc=$result->tc;
				$data['id']=$tc->id;
				$data['tag']=$tc->tag;
				$data['result_id']=$res->result_id;
				$data['description']=$tc->description();
				$data['result']=$result->result;
				$data['updated_at']=$res->updated_at;
				$data['build']=$result->testjob->name.':'.$result->testjob->build->name;
				$datas[]=$data;
			}
		return $datas;
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
		foreach($docker->dest() as $dests){
			array_push($docs,$dests);
		}
		while($doc=array_shift($docs)){
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