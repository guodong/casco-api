<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class VerificationController extends BaseController{
    
	public $column_prefix='(P) // (C)';
	
	public function show($id)
	{
		return Verification::find($id);
	}
    
	public function destroy($id){
            
		$ver=Verification::find($id);
		if($ver->child_matrix){
        $ver->child_matrix->each(function($u){ 	
        	$u->delete();
        });
		}
		if($ver->parent_matrix){
        $ver->parent_matrix->each(function($u){
        	$u->delete();
        });
		}
       //删掉关系记录
        DB::table('verification_parent_version')->where('verification_id','=',$id)->delete();
        $ver->delete();
        return $ver;
            
    
	}
	
	public function index()
	{
		$vefs = Project::find(Input::get('project_id'))->verifications;
		foreach ($vefs as $v){
			$v->childVersion->document;
			foreach($v->parentVersions as $parent){
				$parent->document;
			}

		}
		return $vefs;
	}
    
	public function summary(){
		
		//尼玛根据某个verison来哦,version是唯一的罢
		Input::get('version')?($version=Input::get('verison')):($version=(Verification::orderBy('created_at','desc')->first()->version));
		$ver=Verification::where('version','=',$version)->orderBy('created_at','desc')->first();
		if(!$ver)return [];
		$ans=[];     
		//从数据库中取太慢了吧
		$child=$ver->childVersion;
		$middleware=DB::table('child_matrix')->select(DB::raw('count(*) as num'))->where('verification_id','=',$ver->id);	
		$num=$middleware->first(); 
	    $num_ok=$middleware->where('Traceability','like','%OK%')->first();
        $num_nok=$middleware->where('Traceability','like','%NOK%')->first();
        $num_na=$middleware->where('Traceability','like','%NA%')->first();
		$ans[]=array('doc_name'=>$child->document->name,'nb of req'=>$num->num,'nb req OK'=>$num_ok->num,'nb req NOK'=>$num_nok->num,'nb req NA'=>$num_na->num,'Percent of completeness'=>($num->num!=0)?floatval($num_ok->num)/floatval($num->num):0);		
		
        foreach($ver->parentVersions as $parent){
        //	var_dump($parent->document->name);return;
        $middleware=DB::table('parent_matrix')->select(DB::raw('count(*) as num'))->where('verification_id','=',$ver->id)->where('parent_v_id','=',$parent->id);
	    $num=$middleware->first();
	    $num_ok=$middleware->where('Completeness','like','%OK%')->first();
        $num_nok=$middleware->where('Completeness','like','%NOK%')->first();
        $num_na=$middleware->where('Completeness','like','%NA%')->first();
		$ans[]=array('doc_name'=>$parent->document->name,'nb of req'=>$num->num,'nb req OK'=>$num_ok->num,'nb req NOK'=>$num_nok->num,'nb req NA'=>$num_na->num,'Percent of completeness'=>($num->num!=0)?floatval($num_ok->num)/floatval($num->num):0);
        } 
		return  $ans;
			
	}
	
	
	public function store()
	{
		$job = Verification::create(Input::get());
		$job->status = 0;
		$job->author=Input::get('account')?Input::get('account'):null;
		foreach (Input::get('parent_versions') as $v){
			array_key_exists('parent_version_id',$v)?$job->parentVersions()->attach($v['parent_version_id']):'';
		}
		$job->save();
		$array_child=array();$parent_array=array();
		$parent_vids=[];
		foreach($job->parentVersions as $vs){$parent_vids[]=$vs->id;}
		$parent_items=$job->childVersion->parent_item($parent_vids);
		switch($job->childVersion->document->type){
			case 'tc':
				$array_child=Tc::where('version_id','=',$job->child_version_id)->get()->toArray();
				break;
			case  'rs':
				$array_child=Rs::where('version_id','=',$job->child_version_id)->get()->toArray();
				break;
			default:
		}
		$comment='';$column=[];$child_text='';$parent_text='';
		foreach($array_child as $child){
			foreach($parent_items[0] as  $parent){//for循环结束就是空行记录的了
				$column=[];
				$parent_column=json_decode('{'.$parent['column'].'}',true);
				$child_column=json_decode('{'.$child['column'].'}',true);
				if($child_column&&array_key_exists('source',$child_column)&&in_array($parent['tag'],explode(',',$child_column['source'])))
				{
					if($child_column&&$parent_column){
						foreach($parent_column as $key=>$value){
							if($key=='contribution'){array_key_exists('safety',$child_column)?$column[]=array($key,$value.' // '.$child_column['safety']):$column[]=array($key,$value.' // ');}
							array_key_exists($key,$child_column)?$column[]=array($key=>$child_column[$key].' // '.$value)
							:$column[]=array($key=>$value.' // ');
						}//foreach
                    // var_dump($column);
					}//if
					array_key_exists('description',$child_column)?$child_text=$child_column['description']:array_key_exists('test case description',$child_column)?$child_text=$child_column['test case description']:'';
					array_key_exists('description',$parent_column)?$parent_text=$parent_column['description']:'';


					$array=array('Child Requirement Tag'=>$child['tag'],
                	             'Child Requirement Text'=>$child_text,
                	             'Parent Requirement Tag'=>$parent['tag'],
                	             'Parent Requirement Text'=>$parent_text,
                                 'column'=>json_encode($column[0]),
                                 'verification_id'=>$job->id
					);
					//var_dump($array);
					ChildMatrix::create($array);
						
				}//if
			}//foreach
		}//foreach child_matrix
		 
		 
		 
		 
		foreach($parent_items[0] as  $parent){
			//var_dump($parent);return;
			$flag=false;$column=[];
			$parent_column=json_decode('{'.$parent['column'].'}',true);
			foreach($array_child as $child){
				$child_column=json_decode('{'.$child['column'].'}',true);
				if($child_column&&array_key_exists('source',$child_column)&&in_array($parent['tag'],explode(',',$child_column['source'])))
				{
					$flag=true;
					foreach($parent_column as $key=>$value){
						if($key=='contribution'){array_key_exists('safety',$child_column)?$column[]=array($key,$value.' // '.$child_column['safety']):$column[]=array($key,$value.' // ');}
						array_key_exists($key,$child_column)?$column[]=array($key=>$child_column[$key].' // '.$value)
						:$column[]=array($key=>$value.' // ');
					}//foreach
					array_key_exists('description',$child_column)?$child_text=$child_column['description']:array_key_exists('test case description',$child_column)?$child_text=$child_column['test case description']:'';
					array_key_exists('description',$parent_column)?$parent_text=$parent_column['description']:'';
					$array=array('Parent Requirement Tag'=>$parent['tag'],
                	             'Parent Requirement Text'=>$parent_text,
                  				 'Child Requirement Tag'=>$child['tag'],
                	             'Child Requirement Text'=>$child_text,
                                 'column'=>json_encode($column[0]),
					             'parent_v_id'=>$parent['version_id'],
                                 'verification_id'=>$job->id
					);
					//var_dump($array);
					ParentMatrix::create($array);
				}//if
			}//foreach
			if(!$flag){
				$column=[];
				foreach($parent_column as $key=>$value){
					$column[]=array($key=>$value.' // ');
				}
				array_key_exists('description',$parent_column)?$parent_text=$parent_column['description']:'';
				$array=array('Parent Requirement Tag'=>$parent['tag'],
            	             'Parent Requirement Text'=>$parent_text,
                             'column'=>json_encode($column[0]),
				             'parent_v_id'=>$parent['version_id'],
                             'verification_id'=>$job->id
				);
				//var_dump($array);
				ParentMatrix::create($array);
			}
		}//foreach


		return $job;
	}

	public function update()
	{
		$t = Testjob::find(Input::get('id'));
		$t->update(Input::get());
		return $t;
	}

	public function rsversion()
	{
		$job = Testjob::find(Input::get('job_id'));
		$rsvs = $job->rsVersions;
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
			if(!file_exists($path)){mkdir($path);}
			// $handler=opendir($path); //打开当前文件夹由$path指定。
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
		//chmod($path,0755);
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
		$job = Testjob::find(Input::get("job_id"));
		$results = $job->results;
		$user = User::find(Session::get('uid'));
		 
		include PATH_BASE . '/PE/Classes/PHPExcel.php';
		include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('20');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('20');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('20');


		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Tc tag');

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Description');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Tester');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Begin at');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'End at');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Result');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'Comment');
		$row = 2;
		foreach ($results as $v){
			$tc = $v->tc;
			$startrow = $row;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $tc->tag);
			$item=json_decode('{'.$tc->column.'}',true);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, array_key_exists('description',$item)?$item['description']:$item['test case description']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $user->realname);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, substr($v->begin_at, 0, 1)=='0'?'':$v->begin_at);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, substr($v->end_at, 0, 1)=='0'?'':$v->end_at);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $v->result == 0?'untested':($v->result == 1?'passed':'failed'));
			$cod = $row;
			$step_count = 0;

			foreach ($tc->steps as $step){

				$id=json_decode($step->toJson())->id;
				$stepResult = ResultStep::where('result_id', $v->id)->where('step_id',$id)->first();
					
				if(!$stepResult)continue;
				// echo ($stepResult->step_id);
				$r = $stepResult->result == 0?'untested':($stepResult->result == 1?'passed':'failed');
				if ($stepResult->comment){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $cod++, '#'.$step->num . ' ' . $r . ': ' . $stepResult->comment);
					$step_count++;
				}
			}
			/*
			 $stepResult = ResultStep::where('result_id', $v->id)->get();
			 foreach($stepResult as $value){
			 $r = $value->result == 0?'untested':($value->result == 1?'passed':'failed');
			 if($value->comment){

			 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $cod++, '#'.$->num . ' ' . $r . ': ' . $value->comment);
			 $step_count++;

			 }
			 }
			 */


			if ($step_count>1){
				$row += $step_count-1;
			}
			$endrow = $row;

			$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $startrow, 0, $endrow);
			$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(1, $startrow, 1, $endrow);
			$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(2, $startrow, 2, $endrow);
			$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $startrow, 3, $endrow);
			$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(4, $startrow, 4, $endrow);
			$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(5, $startrow, 5, $endrow);
			$row++;
		}
		 
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