<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
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
				// $parents=(count($parent_items)>0)?$parent_items:[];
				switch($job->childVersion->document->type){
					case 'tc':
						$array_child=Tc::where('version_id','=',$job->child_version_id)->get()->toArray();
						break;
					case  'rs':
						$array_child=Rs::where('version_id','=',$job->child_version_id)->get()->toArray();
						break;
					default:
				}
			}//if
			$comment='';$column=[];$child_text='';$parent_text='';
			foreach($array_child as $child){
				foreach($parent_items  as  $key=>$parents){//有可能多个父类
					foreach($parents as  $parent){//for循环结束就是空行记录的了
						$column=[];
						$parent_column=json_decode('{'.$parent['column'].'}',true);
						$child_column=json_decode('{'.$child['column'].'}',true);
						($child_column&&array_key_exists('source',$child_column))?preg_match_all('/\[.*?\]/i',$child_column['source'],$matches):($matches[0]=[]);
						if(in_array($parent['tag'],$matches[0]))
						{
							$array=array(
								 'child_id'=>$child['id'],
								 'Child Requirement Tag'=>$child['tag'],
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
			}//foreach child_matrix
			//可能有多个父类因此要加层循环结构
			foreach($parent_items  as  $key=>$parents){
				foreach($parents as  $parent){
					//var_dump($parent);return;
					$flag=false;
					$parent_column=json_decode('{'.$parent['column'].'}',true);
					foreach($array_child as $child){
						//$column[]的位置很重要
						$column=[];$child_column=json_decode('{'.$child['column'].'}',true);
						($child_column&&array_key_exists('source',$child_column))?preg_match_all('/\[.*?\]/i',$child_column['source'],$matches):($matches[0]=[]);
						if(in_array($parent['tag'],$matches[0]))
						{
							$flag=true;
							$array=array(
							 	 'parent_id'=>$parent['id'],
								 'Parent Requirement Tag'=>$parent['tag'],
                	   			 'parent_type'=>Version::find($parent['version_id'])->document->type,
                  				 'child_type'=>$job->childVersion->document->type,
								 'Child Requirement Tag'=>$child['tag'],
                	             'child_id'=>$child['id'],
					             'parent_v_id'=>$parent['version_id'],
                                 'verification_id'=>$job->id
							);
							//var_dump($array);
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
						//var_dump($array);
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
		if (Input::get('version')) {
			$version = Input::get('verison');
		} else
		if ($version) {
			$version = $version;
		} else {
			$version = (string) (Verification::orderBy('created_at', 'desc')->first()->version);
		}
		$ver = Verification::where('version', '=', $version)->orderBy('created_at', 'desc')->first();
		if (! $ver)
		return [];
		$ans = [];
		// 从数据库中取太慢了吧,使用count就行了
		$child = $ver->childVersion;
		//从结果中继续查找了吧。。。
		//         $middleware = DB::table('child_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id);
		//         $middleware_nok = $middleware->where('Verif_Assessment', 'like', 'NOK');    //和顺序有关？
		//         $num = $middleware->count();
		//         $num_ok = $middleware->where('Verif_Assessment', 'like', 'OK')->count();
		//         $middleware_nok = $middleware->where('Verif_Assessment', 'like', 'NOK');
		//         $num_nok = $middleware_nok->count();
		//         $num_na = $middleware->where('Verif_Assessment', 'like', 'NA')->first();
		//         $defefct_num = $middleware_nok->where('Already described in completeness','like','NO')->first();
		$num=DB::table('child_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->count();
		$num_ok=DB::table('child_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('Verif_Assessment', 'like', 'OK')->count();
		$num_nok=DB::table('child_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('Verif_Assessment', 'like', 'NOK')->count();
		$num_na=DB::table('child_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('Verif_Assessment', 'like', 'NA')->count();
		$defect_num=DB::table('child_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('Verif_Assessment', 'like', 'NOK')->where('Already described in completeness','not like','YES')->count();
		$ans[] = array(
            'doc_name' => $child->document->name . c_prefix,
            'nb of req' => $num,
            'nb req OK' => $num_ok,
            'nb req NOK' => $num_nok,
            'nb req NA' => $num_na,
            'Percent of completeness' => ($num != 0) ? round(($num_ok / $num) * 100) . '%' : 0,
            'defect_num' => $defect_num,
            'not_complete' => 'X',
            'wrong_coverage' => 'X',
            'logic_error' => 'X',
            'other' => 'X'
            );
            foreach ($ver->parentVersions as $parent) {
            	//             $middleware = DB::table('parent_matrix')->select(DB::raw('count(*) as num'))
            	//                 ->where('verification_id', '=', $ver->id)
            	//                 ->where('parent_v_id', '=', $parent->id);
            	//             $num = $middleware->count();
            	//             $middleware_nok = $middleware->where('Verif_Assesst', 'like', 'NOK');
            	//             $num_ok = $middleware->where('Verif_Assesst', 'like', 'OK')->count();
            	//             $num_nok = $middleware_nok->count();
            	//             $num_na = $middleware->where('Verif_Assesst', 'like', 'NA')->count();
            	//             $middleware = ParentMatrix::where('verification_id', '=', $ver->id);
            	//             $defect_num = $middleware->where('Completeness', 'like', 'NOK')->count();;
            	//             $not_complete = $middleware_nok->where('Defect Type', 'like', 'Not complete')->count();
            	//             $wrong_coverage = $middleware_nok->where('Defect Type', 'like', 'Wrong coverage')->count();
            	//             $logic_error = $middleware_nok->where('Defect Type', 'like', 'logic or description mistake')->count();
            	//            可以使用闭包,孩子
            	$num=DB::table('parent_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('parent_v_id', '=', $parent->id)->count();
            	$num_ok=DB::table('parent_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('parent_v_id', '=', $parent->id)->where('Verif_Assesst', 'like', 'OK')->count();
            	$num_nok=DB::table('parent_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('parent_v_id', '=', $parent->id)->where('Verif_Assesst', 'like', 'NOK')->count();
            	$num_na=DB::table('parent_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('parent_v_id', '=', $parent->id)->where('Verif_Assesst', 'like', 'NA')->count();
            	$not_complete=DB::table('parent_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('parent_v_id', '=', $parent->id)->where('Verif_Assesst', 'like', 'NOK')->where('Defect Type', 'like', '%Not complete%')->count();
            	$wrong_coverage=DB::table('parent_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('parent_v_id', '=', $parent->id)->where('Verif_Assesst', 'like', 'NOK')->where('Defect Type', 'like', '%Wrong coverage%')->count();
            	$logic_error=DB::table('parent_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('parent_v_id', '=', $parent->id)->where('Verif_Assesst', 'like', 'NOK')->where('Defect Type', 'like', '%logic or description mistake%')->count();
            	$other=DB::table('parent_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id)->where('parent_v_id', '=', $parent->id)->where('Verif_Assesst', 'like', 'NOK')->where('Defect Type', 'like', 'Other')->count();

            	$ans[] = array(
                'doc_name' => $parent->document->name . p_prefix,
                'nb of req' => $num,
                'nb req OK' => $num_ok,
                'nb req NOK' => $num_nok,
                'nb req NA' => $num_na,
                'Percent of completeness' => ($num != 0) ? round(($num_ok / $num) * 100) . '%' : 0,
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