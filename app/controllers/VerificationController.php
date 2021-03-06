<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Monolog\Handler\NullHandler;
class VerificationController extends ExportController
{
	public function index()
	{
		//$vefs = Project::find(Input::get('project_id'))->verifications;
		if(!Input::get('child_id'))return [];
		$vefs=Verification::where('project_id','=',Input::get('project_id'))->orderBy('created_at','desc')->get();
		$ans=[];
		foreach ($vefs as $v){
			//&&短路原则很有用
			if($v->childVersion&&$v->childVersion->document->id==Input::get('child_id')){
				foreach($v->parentVersions as $parent){
					$parent->document;
				}
			 $ans[]=$v;
			}//if
		}
			
		return $ans;
	}
	public function  show($id){
		$vefs=[];
		$vefs=Verification::find($id);
		return $vefs;
		 
		 
	}
	public function destroy($id){
		$ver=Verification::find($id);
		if(!$ver) return [];
		$ver->childMatrix->each(function($u){
			$u->delete();
		});
		$ver->parentMatrix->each(function($u){
			$u->delete();
		});
		//删掉关系记录
		DB::table('verification_parent_version')->where('verification_id','=',$id)->delete();
		$ver->delete();
		return $ver;
	}

	public function store()
	{
		//增加事务处理
		DB::beginTransaction();
		try{
			$job = Verification::create(Input::get());
			$job->status = 1;
			$job->author=Input::get('account')?Input::get('account'):null;
			foreach (Input::get('parent_versions') as $v){
				array_key_exists('parent_version_id',$v)?$job->parentVersions()->attach($v['parent_version_id']):'';
			}
			$array_child=array();$parent_array=array();$parent_items=[];$parents=[];
			$parent_vids=[];
			foreach($job->parentVersions as $vs){$parent_vids[]=$vs->id;$parent_types[]=$vs->document->type;}
			if($job->childVersion)
			{
				//返回是二维数组的结构
				$parent_items=$job->childVersion->parent_item($parent_vids);
				switch($job->childVersion->document->type){
					case 'tc':
						$array_child=Tc::where('version_id','=',$job->child_version_id)->get();
						break;
					case  'rs':
						$array_child=Rs::where('version_id','=',$job->child_version_id)->get();
						break;
					default:
				}
			}//if
			$comment='';$column=[];$child_text='';$parent_text='';
			foreach($array_child as $child){
				$flag=false;
				foreach($parent_items  as  $key=>$parents){//有可能多个父类
					foreach($parents as  $parent){//for循环结束就是空行记录的了
						$column=[];
						if(in_array($parent['tag'],$child?$child->sources():[]))
						{	$flag=true;
							$array=array(
								 'child_id'=>$child->id,
								 'Child Requirement Tag'=>$child->tag,
								 'child_type'=>$job->childVersion->document->type,
                	            					 'parent_id'=>$parent['id'],
								 'Parent Requirement Tag'=>$parent['tag'],
                               	 				 'parent_type'=>Version::find($parent['version_id'])->document->type,
                                 					 'verification_id'=>$job->id
							);
							ChildMatrix::create($array);
						}//if
					}//foreach
				}//foreach parent_items
				if(!$flag){
						$column=[];
						$array=array(
								 'child_id'=>$child->id,
								 'Child Requirement Tag'=>$child->tag,
								 'child_type'=>$job->childVersion->document->type,
                	             //'parent_id'=>$parent['id'],
								 //'Parent Requirement Tag'=>$parent['tag'],
                               	 //'parent_type'=>Version::find($parent['version_id'])->document->type,
                                 'verification_id'=>$job->id
							);
						ChildMatrix::create($array);
				}//if $flag
			}//foreach child_matrix
			//可能有多个父类因此要加层循环结构
			foreach($parent_items  as  $key=>$parents){
				foreach($parents as  $parent){
					$flag=false;
					foreach($array_child as $child){
						//$column[]的位置很重要
						if(in_array($parent['tag'],$child->sources()))
						{
							$flag=true;
							$array=array(
							 	 'parent_id'=>$parent['id'],
								 'Parent Requirement Tag'=>$parent['tag'],
                	   			 			'parent_type'=>Version::find($parent['version_id'])->document->type,
                  				 			'child_type'=>$job->childVersion->document->type,
								 'Child Requirement Tag'=>$child->tag,
                	             					 'child_id'=>$child->id,
					             		'parent_v_id'=>$parent['version_id'],
                                					 'verification_id'=>$job->id
							);
							ParentMatrix::create($array);
						}//if
					}//foreach
					if(!$flag){
						$column=[];
						$array=array(
								 'parent_id'=>$parent['id'],
								 'Parent Requirement Tag'=>$parent['tag'],
                	   			 			 'parent_type'=>Version::find($parent['version_id'])->document->type,
                  				 			 'child_type'=>null,
								 'Child Requirement Tag'=>null,
                	             					'child_id'=>null,
					             		'parent_v_id'=>$parent['version_id'],
                                 					'verification_id'=>$job->id
						);
							ParentMatrix::create($array);
					}
				}//foreach
			}//foreach parent_items
			$job->save();//失败了就不save了
			DB::commit();
		}catch(Exception $e){
			DB::rollback();
			return   array('success'=>false,'data'=>($e));
		}
		return  array('success'=>true,'data'=>$job);
	}
	
	public function summary($version = null)
	{
		define('c_prefix', '_Tra');
		define('p_prefix', '_Com');
		if(!Input::get('v_id')) return [];
// 		$ver=DB::table('verification')->where('id',Input::get('v_id'))->get(); //array
//      $ver=Verification::where('id','=',Input::get('v_id'))->get(); //Eloquent collection{object}
        $ver=Verification::find(Input::get('v_id')); //Object
		if (!$ver) return [];
		$ans = [];
		$child = $ver->childVersion;
        $cm=DB::table('child_matrix')->where('verification_id',$ver->id)->orderBy('Child Requirement Tag','asc')->get();
        $info=[];$cm_tag='';
        $num=count($cm);
        $num_ok=$num_nok=$num_na=$defect_num=0;
		for($i=0;$i<$num;$i++){
		    $cm_i=(array)$cm[$i];
// 		    var_dump($cm_i);
		    if($cm_tag!=$cm_i['Child Requirement Tag']){
		        $cm_tag=$cm_i['Child Requirement Tag'];
		        $tmp=array(
		            'c_tag'=>$cm_tag,
		            'c_va_na'=>0,
		            'c_va_ok'=>0,
		            'c_va_nok'=>0,
		            'c_adic'=>0,
		        );
		       while($cm_tag==$cm_i['Child Requirement Tag']){
		            switch($cm_i['Verif_Assessment']){
		                case 'NA':
		                    $tmp['c_va_na']++;break;
		                case 'OK':
		                    $tmp['c_va_ok']++;break;
		                case 'NOK':
		                    $tmp['c_va_nok']++;break;
		                default:break;
		            }
		            if('NOK'==$cm_i['Verif_Assessment'] && (!$cm_i['Already described in completeness'] || 'NO'==$cm_i['Already described in completeness']))
		                $tmp['c_adic']++;
		            if($i+1>=$num) break;
		            $cm_i=(array)$cm[++$i];
		        }
		        if($tmp['c_va_na']) $num_na++;
		        else if($tmp['c_va_nok']) $num_nok++;
		        else $num_ok++;
		        $defect_num+=$tmp['c_adic'];
		        $info[]=$tmp; 
		        $i--;
		    }
		}
		$num=count($info);
		
// 		$defect_num=DB::table('child_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('Verif_Assessment', 'like', 'NOK')->where(function($query){
// 		    $query->where('Already described in completeness','like','NO')->orWhere('Already described in completeness',null);
// 		})->count();
		$ans[] = array(
            'doc_name' => $child->document->name . c_prefix,
            'nb of req' => $num,
            'nb req OK' => $num_ok,
            'nb req NOK' => $num_nok,
            'nb req NA' => $num_na,
            'Percent of completeness' => ($num != 0) ? round(($num_ok / $num) * 100, 2) . '%' : 0,
            'defect_num' => $defect_num,
            'not_complete' => 'X',
            'wrong_coverage' => 'X',
            'logic_error' => 'X',
            'other' => 'X'
            );
		
		foreach ($ver->parentVersions as $parent){
		    $cm=DB::table('parent_matrix')->where('verification_id',$ver->id)->where('parent_v_id',$parent->id)->orderBy('Parent Requirement Tag','asc')->get();
		    $info=[];$cm_tag='';
		    $num=count($cm);
		    $num_ok=$num_nok=$num_na=$defect_num=$not_complete=$wrong_coverage=$logic_error=$other=0;
		    for($i=0;$i<$num;$i++){
		        $cm_i=(array)$cm[$i];
		        // 		    var_dump($cm_i);
		        if($cm_tag!=$cm_i['Parent Requirement Tag']){
		            $cm_tag=$cm_i['Parent Requirement Tag'];
		            $tmp=array(
		                'c_tag'=>$cm_tag,
		                'c_va_na'=>0,
		                'c_va_ok'=>0,
		                'c_va_nok'=>0,
		                'c_nok_nc'=>0,
		                'c_nok_wc'=>0,
		                'c_nok_le'=>0,
		                'c_nok_o'=>0,
		            );
		            while($cm_tag==$cm_i['Parent Requirement Tag']){
		                switch($cm_i['Verif_Assesst']){
		                    case 'NA':
		                        $tmp['c_va_na']++;break;
		                    case 'OK':
		                        $tmp['c_va_ok']++;break;
		                    case 'NOK':
		                        $tmp['c_va_nok']++;break;
		                    default:break;
		                }
		                if('NOK'==$cm_i['Verif_Assesst']){
		                    switch ($cm_i['Defect Type']){
		                        case 'Not complete':
		                            $tmp['c_nok_nc']++;break;
		                        case 'Wrong coverage':
		                            $tmp['c_nok_wc']++;break;
		                        case 'logic or description mistake in Chil':
		                        case 'logic or description mistake':
		                            $tmp['c_nok_le']++;break;
		                        case 'Other':
		                            $tmp['c_nok_o']++;break;
		                            default:break;
		                    }
		                }
		                if($i+1>=$num) break;
		                $cm_i=(array)$cm[++$i];
		           }
		          if($tmp['c_va_na']) $num_na++;
		          else if($tmp['c_va_nok']) $num_nok++;
		          else $num_ok++;
		          $not_complete+=$tmp['c_nok_nc'];
		          $wrong_coverage+=$tmp['c_nok_wc'];
		          $logic_error+=$tmp['c_nok_le'];
		          $other+=$tmp['c_nok_o'];
		          $info[]=$tmp;
		          $i--;
		        }
		    }
		    $num=count($info);
		    $ans[] = array(
		        'doc_name' => $parent->document->name . p_prefix,
		        'nb of req' => $num,
		        'nb req OK' => $num_ok,
		        'nb req NOK' => $num_nok,
		        'nb req NA' => $num_na,
		        'Percent of completeness' => ($num != 0) ? round(($num_ok / $num) * 100, 2) . '%' : 0,
		        'defect_num' => $num_nok,
		        'not_complete' => $not_complete,
		        'wrong_coverage' => $wrong_coverage,
		        'logic_error' => $logic_error,
		        'other' => $other
		    );
		}
		return $ans;
	}

	public function update($id)
	{
		$t = Verification::find($id);
		$t->update(Input::get());
		if (! Input::get('data'))
		return $t;
		foreach (Input::get('data') as $item) {
			// $t->child_matrix->attach(json_encode($item));
			($matrix = ChildMatrix::find($item['id'])) || ($matrix = ParentMatrix::find($item['id']));
			$matrix->update($item);
		}
		return $t;
	}
	public function rsversion()
	{
		$job = Testjob::find(Input::get('job_id'));
		$rsvs = $job->rsVersions;
	}
	public function addFileToZip($path, $zip)
	{
		$handler = opendir($path); // 打开当前文件夹由$path指定。
		while (($filename = readdir($handler)) !== false) {
			if ($filename != "." && $filename != "..") { // 文件夹文件名字为'.'和‘..’，不要对他们进行操作
				if (is_dir($path . "/" . $filename)) { // 如果读取的某个对象是文件夹，则递归
					$this->addFileToZip($path . "/" . $filename, $zip);
				} else { // 将文件加入zip对象
					$zip->addFile($path . "/" . $filename);
				}
			}
		}
		@closedir($path);
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
	public function export_pro()
	{
		$job = Testjob::find(Input::get("job_id"));
		$results = $job->results;
		$zip = new ZipArchive();
		foreach ($results as $v) {
			$tc = $v->tc;
			$path = "./case";
			if (! file_exists($path)) {
				mkdir($path);
			}
			// $handler=opendir($path); //打开当前文件夹由$path指定。
			$filename = $path . "/" . trim($tc->tag, "[]");
			! file_exists($filename) ? mkdir($filename) : null;
			$fp = fopen($filename . '/checklog.py', "wb");
			fwrite($fp, $tc->checklog);
			fclose($fp);
			$robot = $filename . '/' . trim($tc->tag, "[]") . '.robot';
			$fp = fopen($robot, "wb");
			fwrite($fp, $tc->robot);
			fclose($fp);
		} // foreach

		$zip_name = 'result.zip';
		$fp_zip = fopen($zip_name, "wb");
		if ($zip->open($zip_name, ZipArchive::OVERWRITE) === TRUE) {
			$this->addFileToZip($path, $zip);
		}
		fclose($fp_zip);
		$zip->close();
		// chmod($path,0755);
		header("Cache-Control: max-age=0");
		header("Content-Description: File Transfer");
		header('Content-disposition: attachment; filename=' . basename($zip_name)); // 文件名
		header("Content-Type: application/zip"); // zip格式的
		header("Content-Transfer-Encoding: binary"); // 告诉浏览器，这是二进制文件
		header('Content-Length: ' . filesize($zip_name)); // 告诉浏览器，文件大小
		@readfile($zip_name); // 输出文件;
		$this->del($zip_name);
		$this->del($path);
		// rmdir($path);
	}
	public function summary_export()
	{

		$objPHPExcel=parent::summary_exp(Input::get('v_id'),0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="summary.xls"');
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
	public function export(){
		//导出所有的report啊我去
		$project_id=Input::get('project_id');
		$child_id=Input::get('child_id');
		$active_sheet=0;
		$objPHPExcel=parent::export_report($project_id,$child_id,$active_sheet);
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