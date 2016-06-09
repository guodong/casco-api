<?php

use Illuminate\Support\Facades\Input;
class ReportVerifyController extends  BaseController{

    public function index()
    {
    	$datas=[];
        $verify =ReportVerify::where('report_id', '=', Input::get('report_id'))->get();
        if(!$verify) return $datas;
        foreach($verify  as $ver){
        $array=(array)explode(',',$ver->tc_id);
        $ans=Tc::whereIn('id',$array)->select('tag')->get()->toArray();
        $results=Result::where('testjob_id',$ver->report->testjob->id)->whereIn('tc_id',$array)->select('result')->get()->toArray();
		//注意有个最新版本的对应关系问题哦
		  $data[]=$ver;
		  $data['test_case']=implode(',',$this->array_column($ans,'tag'));
		  $data['tag']=Rs::find($ver->rs_id)->tag;
		  $data['result']=array_product($results);
		  $data['description']=Rs::find($ver->rs_id)->column_text();
		  $datas[]=$data;
        }//遍历$verify
        return $datas;
    }
    
	public function show($id)
	{
	   return 	 ReportVerify::find($id);
	}
	
	public function store()
	{
	    $build = Build::create(Input::get());
	    return $build;
	} 
	
	public function  destroy($id)
    {
        $build=ReportVerify::find($id);
        $build->destroy($id);
        return  $build;

     }
	
    public function array_column($input,$column_key,$index_key=''){

		if(!is_array($input)) return;
		$results=array();
		if($column_key===null){
			if(!is_string($index_key)&&!is_int($index_key)) return false;
			foreach($input as $_v){
				if(array_key_exists($index_key,$_v)){
					$results[$_v[$index_key]]=$_v;
				}
			}
			if(empty($results)) $results=$input;
		}else if(!is_string($column_key)&&!is_int($column_key)){
			return false;
		}else{
			if(!is_string($index_key)&&!is_int($index_key)) return false;
			if($index_key===''){
				foreach($input as $_v){
					if(is_array($_v)&&array_key_exists($column_key,$_v)){
						$results[]=$_v[$column_key];
					}
				}
			}else{
				foreach($input as $_v){
					if(is_array($_v)&&array_key_exists($column_key,$_v)&&array_key_exists($index_key,$_v)){
						$results[$_v[$index_key]]=$_v[$column_key];
					}
				}
			}

		}
		return $results;
	}


	
}
