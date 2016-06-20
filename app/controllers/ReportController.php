<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class ReportController extends ExportReportController
{
	public function index()
	{
		if(!Input::get('child_id')||!Input::get('project_id'))return [];
		$vefs=Report::where('project_id','=',Input::get('project_id'))->where('child_id',Input::get('child_id'))->orderBy('created_at','desc')->get();
		$ans=[];
		foreach ($vefs as $v){
			//&&短路原则很有
			$ans[]=$v;
		}
		return $ans;
	}
	public function  show($id){
		$vefs=[];
		$vefs=Report::find($id);
		return $vefs;
	}
	public function  get_result(){
		if(!Input::get('report_id'))  return [];
		$report=Report::find(Input::get('report_id'));
		if(!$report) return 'no report';
		$testjob=$report->testjob->results;
		//$testjob=Testjob::find(Input::get('test_id'))->results;
		$datas=[];
		foreach($testjob  as  $tests){
			$tc=Tc::find($tests->tc_id);
			$data['id']=$tc->id;
			$data['tag']=$tc->tag;
			$data['description']=$tc->description();
			$data['result']=$tc->result;
			$data['build']=$report->testjob->name.':'.$report->testjob->build->name;
			$datas[]=$data;
		}
		return $datas;
	}
	public function destroy($id){
		$ver=Report::find($id);
		if(!$ver) return [];
		$ver->delete();
		return $ver;
	}

	public function store()
	{
		//增加事务处理
		DB::beginTransaction();
		try{
			$job = Report::create(Input::get());
			var_dump($job);
			$job->author=Input::get('account')?Input::get('account'):null;
			//此时已经过滤了一次哦
			if(!Input::get('test_id')||!Input::get('child_id')) throw  new  Exception("参数不合法");
			$rs=Testjob::find(Input::get('test_id'))->rsVersions;
			$result=Testjob::find(Input::get('test_id'))->results;
			//从中确定了RS的版本了的
			foreach($rs as $r){
				if($r->document->id==Input::get('child_id'))
				$version=$r;
			}
			$rss=$version->rss;
			foreach($rss  as  $rs)
			{
				$bak_tc=[];
				foreach($result as $re)
				foreach((array)json_decode(Rs::find($rs->id)->vat_json)  as  $vat){
					$vat&&($re->tc_id==$vat->id)&&$bak_tc[]=$re->tc_id;
				}
			 if(count($bak_tc)<=0)continue;
			 //var_dump($rs->tag,$bak_tc);
			 ReportVerify::create(array(
			 'tc_id'=>implode(',',$bak_tc),
			 'rs_id'=>$rs->id,
			 'report_id'=>$job->id,
			 ));
			 //注意有个最新版本的对应关系问题哦
			}//rss
			//还需要建立另外一张表吧
			$this->cover_status($job,$version);
			$job->save();//失败了就不save了
			DB::commit();
		}catch(Exception $e){
			DB::rollback();
			return   array('success'=>false,'data'=>json_encode($e));
		}
		return  array('success'=>true,'data'=>$job);
	}

	public function cover_status($report,$version){
        $testjob=$report->testjob;
		$array_child=$version->rss;
		$parents=$report->testjob->tcVersion->tcs;
		foreach($parents as  $parent){
			$flag=false;
			$parent_column=json_decode('{'.$parent['column'].'}',true);
			foreach($array_child as $child){
				$column=[];$child_column=json_decode('{'.$child['column'].'}',true);
				($child_column&&array_key_exists('source',$child_column))?preg_match_all('/\[.*?\]/i',$child_column['source'],$matches):($matches[0]=[]);
				if(in_array($parent['tag'],$matches[0]))
				{
					$flag=true;
					$array=array(
							 	 'parent_id'=>$parent['id'],
								 'Parent Requirement Tag'=>$parent['tag'],
                	   			 'parent_type'=>'rs',//Version::find($parent['version_id'])->document->type,
                  				 'child_type'=>'tc',///$job->childVersion->document->type,
								 'Child Requirement Tag'=>$child['tag'],
                	             'child_id'=>$child['id'],
					             //'parent_v_id'=>$parent['version_id'],
                                 'report_id'=>$report->id
					);
					//var_dump($array);
					ReportCover::create($array);
				}//if
			}//foreach
			if(!$flag){
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
				ReportCover::create($array);
			}
		}//foreach
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


public function export_all_sheets(){
		
	$objPHPExcel=parent::export_all_sheet(Input::get('project_id'),Input::get('child_id'),Input::get('v_id'));
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="verification_report.xls"');
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