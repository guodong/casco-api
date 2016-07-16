<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class TestjobController extends BaseController{

	public function show($id)
	{
	  $jobs=Testjob::find($id);
	  if(!$jobs)return [];
	  $jobs->build;
	  $jobs->tcVersion&&$jobs->tcVersion->document;
	  $rsVersions=$jobs->rsVersions?$jobs->rsVersions:null;
		foreach ($rsVersions as $vv){
			$vv->document;
		}
	  return $jobs;
	  
	}

	public function index()
	{
		$jobs = Project::find(Input::get('project_id'))->testjobs;
		if(Input::get('child_id')){
			//这个应该是过滤出相应的rs_doc
		$data=[];
		foreach ($jobs as $v){
			if(!$v)continue;
			$rsVersions=$v->rsVersions?$v->rsVersions:null;
			foreach ($rsVersions as $vv){
				if($vv->document->id==Input::get('child_id')){
				$data[]=$v;
				}
				continue;
			}
		}
			return $data;
		}
		foreach ($jobs as $v){
			if(!$v)continue;
			$v->build;
			$v->tcVersion?$v->tcVersion->document:null;
			$rsVersions=$v->rsVersions?$v->rsVersions:null;
			foreach ($rsVersions as $vv){
				$vv->document;
			}
		}
		return $jobs;
	}
	public function destroy($id){
		$job=Testjob::find($id);
		foreach($job->results  as $result){
			foreach($result->steps as  $steps){
				$steps->delete();
			}
			$result->delete();
		}
		foreach($job->rsRelations as $rels){
			$rels->delete();
		}
		$job->destroy($id);
		return $job;
	}
	public function store()
	{
		$job = Testjob::create(Input::get());
		$job->status = 0;
		foreach (Input::get('rs_versions') as $v){
			$job->rsVersions()->attach($v['rs_version_id']);
		}
		$v = $job;
		$v->build;
		$v->tcVersion->document;
		foreach ($v->rsVersions as $vv){
			$vv->document;
		}
		foreach (Input::get('tcs') as $tcid){
			Result::create(array(
	            'tc_id' => $tcid,
	            'testjob_id' => $job->id
			));
		}
		return $job;
	}

	public function update()
	{
		$t = Testjob::find(Input::get('id'));
		$t->update(Input::get());
		return $t;
	}
	
	public function import_tmp()
	{	
		if(!Input::get('name')||!Input::get('project_id'))
			return  array('success'=>false,'data'=>'发送数据错误!');
		$data=Input::get();
    	$file_types = explode ( ".", $_FILES ["exceltpl"] ["name"] );
    	$file_type = $file_types[count ( $file_types ) - 1];
    	$data['type']=$file_type;
		$name = uniqid () . '.'.$file_type;
		$name=public_path () . '/exceltpls/' . $name;
		move_uploaded_file ( $_FILES ["exceltpl"] ["tmp_name"], $name);
		$data['path']= $name;
		$data['size']=@filesize($name);
	    $tmp=TestjobTmp::create($data);
		return  array('success'=>true,'data'=>$tmp);

	}
	public function addFileToZip($path,$zip){

		$handler=opendir($path); //打开当前文件夹由$path指定。
		while(($filename=readdir($handler))!==false){
			if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
				if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
					$this->addFileToZip($path."/".$filename, $zip);
				}else{ //将文件加入zip对象
					$zip->addFile($path."/".$filename);
				}
			}
		}
		@closedir($path);
	}
    
	public	 function del($path)
		{
		    if(is_dir($path))
		    {
		            $file_list= scandir($path);
		            foreach ($file_list as $file)
		            {
		                if( $file!='.' && $file!='..')
		                {
		                    $this->del($path.'/'.$file);
		                }
		            }
		            @rmdir($path);  //这种方法不用判断文件夹是否为空,  因为不管开始时文件夹是否为空,到达这里的时候,都是空的     
		    }
		    else
		    {
		        @unlink($path);    //这两个地方最好还是要用@屏蔽一下warning错误,看着闹心
		    }
		 
		}

	public function export_pro(){


		$job = Testjob::find(Input::get("job_id"));
		$results = $job->results;
		$zip=new ZipArchive();
		foreach ($results as $v){
			$tc = $v->tc;
			$path="./case";
// 			chmod($path,0777);
			if(!file_exists($path)){mkdir($path);}
			$filename=$path."/".trim($tc->tag,"[]");
			!file_exists($filename)?mkdir($filename):null;
			$fp=fopen($filename.'/checklog.py',"wb");
			fwrite($fp,$tc->checklog);
			fclose($fp);
			$robot=$filename.'/'.trim($tc->tag,"[]").'.robot';
			$fp=fopen($robot,"wb");
			fwrite($fp,$tc->robot);
			fclose($fp);
		}//foreach
		$zip_name='result.zip';
		$fp_zip=fopen($zip_name,"wb");
		if($zip->open($zip_name, ZipArchive::OVERWRITE)=== TRUE){
			$this->addFileToZip($path, $zip);
		}
		 fclose($fp_zip);
		 $zip->close();
		header ( "Cache-Control: max-age=0" );
		header ( "Content-Description: File Transfer" );
		header ( 'Content-disposition: attachment; filename=' . basename ( $zip_name ) ); // 文件名
		header ( "Content-Type: application/zip" ); // zip格式的
		header ( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
		header ( 'Content-Length: ' . filesize ( $zip_name ) ); // 告诉浏览器，文件大小
		@readfile ( $zip_name );//输出文件;
		$this->del($zip_name);
		$this->del($path);
        //rmdir($path);
	}


	public function export()
	{	
		//传过来一个文件Id,再去覆盖就好了的
		$tmp=TestjobTmp::find(Input::get('tmp_id'));
		$job = Testjob::find(Input::get("job_id"));
		if(!$job)die("请选择Testing Result!");
		if(!$tmp)die("模板出错!");
		if(!file_exists($tmp->path))die('模板文件不存在!');
		$results = $job->results;
		$user = User::find(Session::get('uid'));
		include PATH_BASE . '/PE/Classes/PHPExcel.php';
		include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
		if( $tmp->type =='xlsx' )
		{
		    $objReader = new PHPExcel_Reader_Excel2007();
		}
		else
		{
		    $objReader = new PHPExcel_Reader_Excel5();
		}
// 		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load($tmp->path);
		$objPHPExcel->setActiveSheetIndex(0);
		$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		//设置列宽
// 		$config_arr = array("A"=>25,"B"=>35,"C"=>15,"D"=>20,"E"=>15,"F"=>15,"G"=>15,"H"=>15,"I"=>30);
// 		foreach ($config_arr as $col=>$config){
// 		    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth($config);
// 		}
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);//自动换行
		$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setWrapText(true);//自动换行
		$start_col=4;
// 		//设置Test job表头
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $start_col, 'Name');
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $start_col, 'Build');
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $start_col, 'TC');
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $start_col, 'TC Version');
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $start_col, 'RS:Version');
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $start_col, 'Status');
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $start_col, 'Created at');
// 		//设置表头格式
// 		$objPHPExcel->getActiveSheet()->getStyle("A$start_col".":"."G$start_col")->getFont()->setName('Arial');
// 		$objPHPExcel->getActiveSheet()->getStyle("A$start_col".":"."G$start_col")->getFont()->setSize(15);
// 		$objPHPExcel->getActiveSheet()->getStyle("A$start_col".":"."G$start_col")->getFont()->setBold(true);
// 		//输出Test job信息
// 		$start_col++;
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $start_col, $job->name);
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $start_col, $job->build->name);
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $start_col, $job->tc_version->document->name);
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $start_col, $job->tc_version->name);
// 		  //处理TC与RS的一对多情况
// 		  $rs_info = '';
// 		  foreach($job->rs_versions as $i){
// 		      $rs_info .= $i->document->name.":".$i->name."; ";
// 		  }
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $start_col, $rs_info);
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $start_col, $job->status == '0' ? 'testing':'submited');
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, substr($job->created_at, 0, 1)=='0'?'':$job->created_at);

		//设置表头项
        $line_origin =$start_col+1;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line_origin, '测试用例编号');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line_origin, '测试用例描述');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line_origin, '通过/失败');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line_origin, '执行时间');
// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_origin, '结束时间');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_origin, '测试人');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line_origin, '校核人');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line_origin, '平台版本');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line_origin, '备注');
		//设置过滤
		$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line_origin.":".'H'.$line_origin);
		//设置表头格式
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_origin.":".'H'.$line_origin)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_origin.":".'H'.$line_origin)->getFont()->setSize(15);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_origin.":".'H'.$line_origin)->getFont()->setBold(true);
		
		//数据填充
		$startrow = $line_origin + 1;
		foreach ($results as $v){
			$tc = $v->tc;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $startrow, $tc->tag);
			$item=json_decode('{'.$tc->column.'}',true);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $startrow, $tc->description());
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $startrow, $v->result == 0?'untested':($v->result == 1?'passed':'failed'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $startrow, substr($v->begin_at, 0, 1)=='0'?'':$v->begin_at);
// 			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $startrow, substr($v->end_at, 0, 1)=='0'?'':$v->end_at);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $startrow, $user->realname);
			//解析comment
			$restult_comment = '';
			$index = 1;
			foreach ($tc->steps as $step){
				$id=json_decode($step->toJson())->id;
				$stepResult = ResultStep::where('result_id', $v->id)->where('step_id',$id)->first();
				
				if(!$stepResult) continue;
				$r = $stepResult->result == 0?'untested':($stepResult->result == 1?'passed':'failed');
				if ($stepResult->comment){
				    $restult_comment .= "Step".intval($index++).' '.$r.': '.$stepResult->comment."\n";
				}
			}
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $startrow, $restult_comment);

			$startrow++;
		}
		
		if($startrow < $highestRow)
		    $objPHPExcel->getActiveSheet()->removeRow($startrow,$highestRow);
	  
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="output.xls"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
	  
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

}