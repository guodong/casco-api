<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Rhumsaa\Uuid\Uuid;
class TestjobController extends BaseController{

	public function show($id)
	{
		$jobs=Testjob::find($id);
		if(!$jobs)return [];
		$jobs->build;
		$jobs->vatbuild;
		return $jobs;
		 
	}

	public function index()
	{
		$jobs = Project::find(Input::get('project_id'))->testjobs;
		if(Input::get('child_id')){
			$data=[];
			foreach ($jobs as $v){
 			if(!$v||!$v->vatbuild||!($tcVersions=$v->vatbuild->tcVersion))continue;
			if($tcVersions->document->id==Input::get('child_id')){
			$data[]=$v;
			}
 			continue;
			}//foreach
			return $data;
		}
		foreach ($jobs as $v){
			if(!$v)continue;
			$v->user;
			$v->build;
			$v->vatbuild&&$v->vatbuild->tcVersion->document;;
			$vss = $v->vatbuild&&$v->vatbuild->rsVersions?$v->vatbuild->rsVersions:[];
			foreach ($vss as $vatrs){
			    $vatrs->document;
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
		$job->destroy($id);
		return $job;
	}
	
	public function store()
	{
		$job = Testjob::create(Input::get());
		$job->status = 0;
		$data=[];
		foreach (Input::get('tcs') as $tcid){
			$data[]=array(
	            'tc_id' => $tcid,
	            'testjob_id' => $job->id,
				'id'=>Uuid::uuid4()
			);
		}
		DB::table('result')->insert($data);
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
		return  array('success'=>false,'data'=>'鍙戦�佹暟鎹敊璇�!');
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

		$handler=opendir($path); //鎵撳紑褰撳墠鏂囦欢澶圭敱$path鎸囧畾銆�
		while(($filename=readdir($handler))!==false){
			if($filename != "." && $filename != ".."){//鏂囦欢澶规枃浠跺悕瀛椾负'.'鍜屸��..鈥欙紝涓嶈瀵逛粬浠繘琛屾搷浣�
				if(is_dir($path."/".$filename)){// 濡傛灉璇诲彇鐨勬煇涓璞℃槸鏂囦欢澶癸紝鍒欓�掑綊
					$this->addFileToZip($path."/".$filename, $zip);
				}else{ //灏嗘枃浠跺姞鍏ip瀵硅薄
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
			@rmdir($path);  //杩欑鏂规硶涓嶇敤鍒ゆ柇鏂囦欢澶规槸鍚︿负绌�,  鍥犱负涓嶇寮�濮嬫椂鏂囦欢澶规槸鍚︿负绌�,鍒拌揪杩欓噷鐨勬椂鍊�,閮芥槸绌虹殑     
		}
		else
		{
			@unlink($path);    //杩欎袱涓湴鏂规渶濂借繕鏄鐢ˊ灞忚斀涓�涓媤arning閿欒,鐪嬬潃闂瑰績
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
		header ( 'Content-disposition: attachment; filename=' . basename ( $zip_name ) ); // 鏂囦欢鍚�
		header ( "Content-Type: application/zip" ); // zip鏍煎紡鐨�
		header ( "Content-Transfer-Encoding: binary" ); // 鍛婅瘔娴忚鍣紝杩欐槸浜岃繘鍒舵枃浠�
		header ( 'Content-Length: ' . filesize ( $zip_name ) ); // 鍛婅瘔娴忚鍣紝鏂囦欢澶у皬
		@readfile ( $zip_name );//杈撳嚭鏂囦欢;
		$this->del($zip_name);
		$this->del($path);
		//rmdir($path);
	}


	public function export()
	{
		//浼犺繃鏉ヤ竴涓枃浠禝d,鍐嶅幓瑕嗙洊灏卞ソ浜嗙殑
		$tmp=TestjobTmp::find(Input::get('tmp_id'));
		$job = Testjob::find(Input::get("job_id"));
		if(!$job)die("璇烽�夋嫨Testing Result!");
		if(!$tmp)die("妯℃澘鍑洪敊!");
		if(!file_exists($tmp->path))die('妯℃澘鏂囦欢涓嶅瓨鍦�!');
		$results = $job->results;
		$user=User::find($job->user_id);
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
		//璁剧疆鍒楀
		// 		$config_arr = array("A"=>25,"B"=>35,"C"=>15,"D"=>20,"E"=>15,"F"=>15,"G"=>15,"H"=>15,"I"=>30);
		// 		foreach ($config_arr as $col=>$config){
		// 		    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth($config);
		// 		}
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);//鑷姩鎹㈣
		$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setWrapText(true);//鑷姩鎹㈣
		$start_col=4;
		// 		//璁剧疆Test job琛ㄥご
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $start_col, 'Name');
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $start_col, 'Build');
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $start_col, 'TC');
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $start_col, 'TC Version');
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $start_col, 'RS:Version');
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $start_col, 'Status');
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $start_col, 'Created at');
		// 		//璁剧疆琛ㄥご鏍煎紡
		// 		$objPHPExcel->getActiveSheet()->getStyle("A$start_col".":"."G$start_col")->getFont()->setName('Arial');
		// 		$objPHPExcel->getActiveSheet()->getStyle("A$start_col".":"."G$start_col")->getFont()->setSize(15);
		// 		$objPHPExcel->getActiveSheet()->getStyle("A$start_col".":"."G$start_col")->getFont()->setBold(true);
		// 		//杈撳嚭Test job淇℃伅
		// 		$start_col++;
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $start_col, $job->name);
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $start_col, $job->build->name);
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $start_col, $job->tc_version->document->name);
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $start_col, $job->tc_version->name);
		// 		  //澶勭悊TC涓嶳S鐨勪竴瀵瑰鎯呭喌
		// 		  $rs_info = '';
		// 		  foreach($job->rs_versions as $i){
		// 		      $rs_info .= $i->document->name.":".$i->name."; ";
		// 		  }
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $start_col, $rs_info);
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $start_col, $job->status == '0' ? 'testing':'submited');
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, substr($job->created_at, 0, 1)=='0'?'':$job->created_at);

		//璁剧疆琛ㄥご椤�
		$line_origin =$start_col+1;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line_origin, '娴嬭瘯鐢ㄤ緥缂栧彿');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line_origin, '娴嬭瘯鐢ㄤ緥鎻忚堪');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line_origin, '閫氳繃/澶辫触');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line_origin, '鎵ц鏃堕棿');
		// 		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_origin, '缁撴潫鏃堕棿');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_origin, '娴嬭瘯浜�');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line_origin, '鏍℃牳浜�');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line_origin, '骞冲彴鐗堟湰');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line_origin, '澶囨敞');
		//璁剧疆杩囨护
		$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line_origin.":".'H'.$line_origin);
		//璁剧疆琛ㄥご鏍煎紡
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_origin.":".'H'.$line_origin)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_origin.":".'H'.$line_origin)->getFont()->setSize(15);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_origin.":".'H'.$line_origin)->getFont()->setBold(true);

		//鏁版嵁濉厖
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
			//瑙ｆ瀽comment
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