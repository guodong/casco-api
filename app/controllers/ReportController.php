<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Rhumsaa\Uuid\Uuid;
class ReportController extends ExportReportController
{
	public function index()
	{
		if(!Input::get('doc_id')||!Input::get('project_id'))return [];
		$vefs=Report::where('project_id','=',Input::get('project_id'))->where('doc_id',Input::get('doc_id'))->orderBy('created_at','desc')->get();
		$datas=[];
		foreach ($vefs as $v){
			//&&短路原则很有，版本的原则哦
			if(!$v->testjob||!$v->testjob->vatbuild)
			{$v['docs']=[];$datas[]=$v;continue;}
			foreach($v->testjob->vatbuild->rsVersions as $value){
				$value->document;
			}
			$v['docs']=$v->testjob->vatbuild->rsVersions;
			$v->testjob->vatbuild->tcVersion->document;
			$datas[]=$v;
		}
		return $datas;
	}
	public function  get_results(){
		//必须要合并好吧

		if(!($tc_id=Input::get('tc_id'))||!($project_id=Input::get('project_id'))) return [];
		$versions_id=$this->array_column(Document::find($tc_id)->versions->toArray(),'id');
		$testjob=Testjob::where('project_id','=',$project_id)->where('status',1)
		->whereIn('tc_version_id',$versions_id)->orderBy('created_at','desc')->get();
		//$testjob=Testjob::find(Input::get('test_id'))->results;
		$datas=[];
		foreach($testjob  as  $tests){
			//var_dump($tests);
			foreach($tests->results as $res){
				$tc=Tc::find($res->tc_id);
				//过滤重复的规则
				$data['id']=$tc->id;
				$data['tag']=$tc->tag;
				$data['result_id']=$res->id;
				$data['description']=$tc->description();
				$data['result']=$res->result;
				$data['created_at']=$res->created_at;
				$data['updated_at']=$res->updated_at;
				$data['build']=$tests->name.':'.$tests->build->name;
				$datas[]=$data;
			}
		}
		return $datas;
	}
	public function  show($id){
		$vefs=[];
		$vefs=Report::find($id);
		return $vefs;
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

	public function  get_result(){
		//必须要合并好吧
		if(!Input::get('report_id'))  return [];
		$report=Report::find(Input::get('report_id'));
		if(!$report) return [];
		$testjob=$report->results;$datas=[];
		foreach($report->results  as $res){
			$tc=Tc::find($res->result->tc_id);
			$data['id']=$tc->id;
			$data['tag']=$tc->tag;
			$data['description']=$tc->description();
			$data['result']=$res->result->result;
			$data['created_at']=$res->created_at;
			$data['updated_at']=$res->updated_at;
			$data['build']=$res->result->testjob->name.':'.$res->result->testjob->build->name;
			$datas[]=$data;
		}
		return $datas;
	}



	public function destroy($id){
		$ver=Report::find($id);
		if(!$ver) return [];
		foreach($ver->covers as $covers)$covers->delete();
		foreach($ver->results as $results)$results->delete();
		foreach($ver->verify as $verify )$verify->delete();
		$ver->delete();
		return $ver;
	}

	public function distinct($arr){
	 $tmp_arr=[];
	 foreach($arr as $k => $v)
	 {
	 	if(in_array($v, $tmp_arr))
	 	//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
	 	{
	 		unset($arr[$k]);
	 		//删除掉数组（$arr）里相同ID的数组
	 	}
		 else {
		 	$tmp_arr[] = $v;
		 	//记录已有的id
		 }
	 }//foreach
	 return $arr;
	}


	public function store()
	{
		//增加事务处理
		DB::beginTransaction();
		try{

			if(!Input::get('project_id')||!Input::get('doc_id')||!Input::get('test_id')) throw  new  Exception("参数不合法");
			$job = Report::create(Input::get());
			$job->author=Input::get('account')?Input::get('account'):null;
			$job->testjob_id=Input::get('test_id');
			$test=Testjob::find(Input::get('test_id'));
			$rencents=$test->rencents();$all_rs=[];$results=$shits=[];$last=1;
			$test->vatbuild&&($all_rs=$test->vatbuild->rsVersions);
			foreach ($rencents as $key=>$value){
				$child=Result::find($value['id'])->tc->toArray();
				$item=Tc::find($child['id']);
				$child['sources']=$item?$item->sources():[];
				$child['result_id']=$value['id'];
				$array_child[]=$child;
				$results[]=$value['tc_id'];$shits[$value['tc_id']]=$value['result'];
				ReportResult::create(array(
		            	'result_id' => $value['id'],
		            	'report_id' => $job->id
				));
			}
			foreach($all_rs as $r){
				$rss=$r->rss;
				foreach($rss  as  $rs)
				{
					$bak_tc=[];
					$vat_id=(array)$this->array_column(json_decode($rs->vat_json,true),'id');
					$bak_tc=array_intersect($vat_id,$results);$res=[];
					if(count($bak_tc)<=0)continue;
					foreach ($bak_tc  as $id){
						if(array_key_exists($id,$shits)){
							$last*=$shits[$id];
						}
					}
					ReportVerify::create(array(
					 'tc_id'=>implode(',',$bak_tc),
					 'doc_id'=>$r->id,//文档的ID
					 'rs_id'=>$rs->id,
					 'result'=>$last,
					 'report_id'=>$job->id
					));
			 }//rss
			}//$r
			//还需要建立另外一张表吧
			$parents=$test->vatbuild->directDests();
			$job->save();//失败了就不save了
			$this->cover_status((array)$parents,(array)$array_child,$job);
			DB::commit();
			return  array('success'=>true,'data'=>$job->id);
		}catch(Exception $e){
			DB::rollback();
			return   array('success'=>false,'data'=>json_encode($e));
		}

	}

	public function cover_status($parents,$array_child,$report){

		$datas=[];$i=1;
		foreach($parents as  $parent){$flag=false;
		foreach($array_child as $child){
			if(in_array($parent['tag'],$child['sources']))
			{
				$flag=true;
				$datas[]=array(
				 	 'parent_id'=>$parent['id'],
					 'Parent Requirement Tag'=>$parent['tag'],
             	   	 'parent_type'=>'rs',//Version::find($parent['version_id'])->document->type,
               		 'child_type'=>'tc',///$job->childVersion->document->type,
					 'Child Requirement Tag'=>$child['tag'],
             	     'child_id'=>$child['id'],
					 'result_id'=>$child['result_id'],
                     'report_id'=>$report->id,
					 'id'=>Uuid::uuid4()
				);
					
			}//if
		}//foreach
		}//foreach
		//var_dump($datas);
		$cover=DB::table('report_cover_status')->insert($datas);
		/*if(!$flag){
		 $column=[];
		 $array=array(
		 'parent_id'=>$parent['id'],
		 'Parent Requirement Tag'=>$parent['tag'],
		 'parent_type'=>'rs',//Version::find($parent['version_id'])->document->type,
		 'child_type'=>null,
		 'Child Requirement Tag'=>null,
		 'child_id'=>null,
		 'report_id'=>$report->id
		 );
		 //var_dump($array);
		 //ReportCover::create($array);
			}*/
	}


	public function del($path)
	{
		if (is_dir($path)) {
			$file_list = scandir($path);
			foreach ($file_list as $file) {
				if ($file != '.' && $file != '..') {
					$this->del($path . '/' . $file);
				}
			}
			@rmdir($path); // 这种方法不用判断文件夹是否为空, 因为不管开始时文件夹是否为空,到达这里的时候,都是空的
		} else {
			@unlink($path); // 这两个地方最好还是要用@屏蔽一下warning错误,看着闹心
		}
	}



	public function export_result(){
		//导出所有的report啊我去
		if(!Input::get('report_id')||!$report=Report::find(Input::get('report_id')))return [];
		$active_sheet=0;
		$objPHPExcel=parent::export_testing($report,$active_sheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="report.xls"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	
	public function export_verify(){
		//导出所有的report啊我去
		if(!Input::get('report_id')||!$report=Report::find(Input::get('report_id')))return [];
		$active_sheet=0;
		$objPHPExcel=parent::export_testing($report,$active_sheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="report.xls"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	

	public function export_all_sheets(){
		
		if(!Input::get('report_id')||!$report=Report::find(Input::get('report_id')))return [];
		$objPHPExcel=parent::export_all_sheet($report);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="report.xls"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

}

?>