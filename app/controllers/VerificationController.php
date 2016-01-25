<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class VerificationController extends BaseController
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
	
	
	public function store()
	{
		$job = Verification::create(Input::get());
		$job->status = 1;
		$job->author=Input::get('account')?Input::get('account'):null;
		foreach (Input::get('parent_versions') as $v){
			array_key_exists('parent_version_id',$v)?$job->parentVersions()->attach($v['parent_version_id']):'';
		}
		$job->save();
		$array_child=array();$parent_array=array();$parent_items=[];$parents=[];
		$parent_vids=[];
		foreach($job->parentVersions as $vs){$parent_vids[]=$vs->id;}
		if($job->childVersion)
		{$parent_items=$job->childVersion->parent_item($parent_vids);
		$parents=(!empty(array_filter($parent_items)))?$parent_items[0]:$parents;
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
			foreach($parents as  $parent){//for循环结束就是空行记录的了
				$column=[];
				$parent_column=json_decode('{'.$parent['column'].'}',true);
				$child_column=json_decode('{'.$child['column'].'}',true);
				if($child_column&&array_key_exists('source',$child_column)&&in_array($parent['tag'],explode(',',$child_column['source'])))
				{
					if($child_column&&$parent_column){
						foreach($parent_column as $key=>$value){
							if($key=='contribution'){array_key_exists('safety',$child_column)?$column[]=array($key,$value.MID_COMPOSE.$child_column['safety']):$column[]=array($key,$value.MID_COMPOSE);}
							if($key=='description'||$key=='test case description'){continue;}
							array_key_exists($key,$child_column)?$column[]=array($key=>$child_column[$key].MID_COMPOSE.$value)
							:$column[]=array($key=>$value.MID_COMPOSE);
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
		foreach($parents as  $parent){
			//var_dump($parent);return;
			$flag=false;$column=[];
			$parent_column=json_decode('{'.$parent['column'].'}',true);
			foreach($array_child as $child){
				$child_column=json_decode('{'.$child['column'].'}',true);
				if($child_column&&array_key_exists('source',$child_column)&&in_array($parent['tag'],explode(',',$child_column['source'])))
				{
					$flag=true;
					foreach($parent_column as $key=>$value){
						if($key=='contribution'){array_key_exists('safety',$child_column)?$column[]=array($key,$value.MID_COMPOSE.$child_column['safety']):$column[]=array($key,$value.MID_COMPOSE);}
						if($key=='description'||$key=='test case description'){continue;}
						array_key_exists($key,$child_column)?$column[]=array($key=>$child_column[$key].MID_COMPOSE.$value)
						:$column[]=array($key=>$value.MID_COMPOSE);
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
					$column[]=array($key=>$value.MID_COMPOSE);
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
        $middleware = DB::table('child_matrix')->select(DB::raw('count(*) as num'))->where('verification_id', '=', $ver->id);
        $num = $middleware->first();
        $item_nok = $middleware->where('Verif_Assessment', 'like', '%NOK%');
        $num_ok = $middleware->where('Verif_Assessment', 'like', '%OK%')->first();
        $num_nok = $item_nok->first();
        $num_na = $middleware->where('Verif_Assessment', 'like', '%NA%')->first();
        $num_tmp = $item_nok->where('Already described in completeness','not like','%YES%')->first();
        $ans[] = array(
            'doc_name' => $child->document->name . c_prefix,
            'nb of req' => $num->num,
            'nb req OK' => $num_ok->num,
            'nb req NOK' => $num_nok->num,
            'nb req NA' => $num_na->num,
            'Percent of completeness' => ($num->num != 0) ? round(($num_ok->num / $num->num) * 100) . '%' : 0,
            'defect_num' => $num_nok->num,
            'not_complete' => 'X',
            'wrong_coverage' => 'X',
            'logic_error' => 'X',
            'other' => 'X'
        );
        foreach ($ver->parentVersions as $parent) {
            $middleware = DB::table('parent_matrix')->select(DB::raw('count(*) as num'))
                ->where('verification_id', '=', $ver->id)
                ->where('parent_v_id', '=', $parent->id);
            $num = $middleware->first();
            $item_nok = $middleware->where('Verif_Assesst', 'like', '%NOK%');
            $num_ok = $middleware->where('Verif_Assesst', 'like', '%OK%')->first();
            $num_nok = $item_nok->first();
            $num_na = $middleware->where('Verif_Assesst', 'like', '%NA%')->first();
            // $ans[]=array('doc_name'=>$parent->document->name,'nb of req'=>$num->num,'nb req OK'=>$num_ok->num,'nb req NOK'=>$num_nok->num,'nb req NA'=>$num_na->num,'Percent of completeness'=>($num->num!=0)?round(($num_ok->num/$num->num)*100).'%':0);
//             $middleware = ParentMatrix::where('verification_id', '=', $ver->id);
//             $defect_num = $middleware->where('Completeness', 'like', '%NOK%')->first();;
            $not_complete = $item_nok->where('Defect Type', 'like', '%Not complete%')->count();
            $wrong_coverage = $item_nok->where('Defect Type', 'like', '%Wrong coverage%')->count();
            $logic_error = $item_nok->where('Defect Type', 'like', '%logic or description mistake%')->count();
            $other = $item_nok->where('Defect Type', 'like', '%Other%')->count();
            $ans[] = array(
                'doc_name' => $parent->document->name . p_prefix,
                'nb of req' => $num->num,
                'nb req OK' => $num_ok->num,
                'nb req NOK' => $num_nok->num,
                'nb req NA' => $num_na->num,
                'Percent of completeness' => ($num->num != 0) ? round(($num_ok->num / $num->num) * 100) . '%' : 0,
                'defect_num' => $num_nok->num,
                'not_complete' => $not_complete,
                'wrong_coverage' => $wrong_coverage,
                'logic_error' => $logic_error,
                'other' => $other
            );
        }
        return $ans;
    }
    public function summary_export()
    {
        include PATH_BASE . '/PE/Classes/PHPExcel.php';
        include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        if (Input::get('version')) {
            $version = Input::get('verison');
        } else {
            $version = (string) (Verification::orderBy('created_at', 'desc')->first()->version);
        }
        $array = $this->summary($version);
        // var_dump($array);exit();
        // 定个坐标，很重要呀
        $circle = array(
            'col' => 'C',
            'row' => 4
        );
        $config = array(
            '',
            'nb of req',
            'nb req OK',
            'nb req NOK',
            'nb req NA',
            'Percent of completeness',
            '',
            '',
            '',
            'Total number of NOK items',
            'The number of NOK items( Not Complete)',
            'The number of NOK items 
	(Wrong Coverage)',
            'The number of NOK items (Logic or Description Mistake)',
            'Other'
        );
        
        foreach ($config as $key => $val) {
            // $key=$key+1;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($circle['col'] . ($circle['row'] + $key), $val);
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension($circle['col'])
                ->setWidth(30);
            $objPHPExcel->getActiveSheet()
                ->getStyle($circle['col'] . ($circle['row'] + $key))
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()
                ->getStyle($circle['col'] . ($circle['row'] + $key))
                ->getFont()
                ->setName('Arial');
            $objPHPExcel->getActiveSheet()
                ->getStyle($circle['col'] . ($circle['row'] + $key))
                ->getFont()
                ->setSize(10);
        }
        $i = 1;
        // 我查二维数组
        foreach ($array as $name => $item) {
            $j = 0;
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension(chr(ord($circle['col']) + $i))
                ->setWidth(25);
            
            foreach ($item as $key => $value) {
                $objPHPExcel->getActiveSheet()
                    ->getStyle(chr(ord($circle['col']) + $i) . ($circle['row'] + $j))
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()
                    ->getStyle(chr(ord($circle['col']) + $i) . ($circle['row'] + $j))
                    ->getFont()
                    ->setName('Arial');
                $objPHPExcel->getActiveSheet()
                    ->getStyle(chr(ord($circle['col']) + $i) . ($circle['row'] + $j))
                    ->getFont()
                    ->setSize(10);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(ord($circle['col']) + $i) . ($circle['row'] + $j), $value);
                if ($j == 5) {
                    $j += 3;
                }
                $j ++; // rows
            } // for
            $i ++; // cols
        } // for
          // 设置左侧version栏
        $cell = chr(ord($circle['col']) - 1) . $circle['row'];
        $range = chr(ord($circle['col']) - 1) . $circle['row'] . ':' . chr(ord($circle['col']) - 1) . ($circle['row'] + $j - 1);
        $objPHPExcel->getActiveSheet()->mergeCells($range);
        $objPHPExcel->getActiveSheet()
            ->getStyle($range)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell, $version);
        $objPHPExcel->getActiveSheet()
            ->getStyle($range)
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="summary.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
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
	public function export(){
		//导出所有的report啊我去	
		$EOF="\r\n";
		$vefs =Verification::where('project_id','=',Input::get('project_id'))->where('child_id','=',Input::get('child_id'))->get();
		include PATH_BASE . '/PE/Classes/PHPExcel.php';
		include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$num=5;
		$array_config=array('C'=>10,'D'=>15,'E'=>20,'F'=>50);
		//表头配置信息
		$column_config=array(
		array('Col'=>'C','Width'=>10,'text'=>'Version','font'=>array('Bold'=>true,'Name'=>'Arial','size'=>10),'Borders'=>PHPExcel_Style_Border::BORDER_THICK,'Fill'=>array('Type'=>PHPExcel_Style_Fill::FILL_SOLID,'Color'=>'0000CDCD')),
		array('Col'=>'D','Width'=>15,'text'=>'Author','font'=>array('Bold'=>true,'Name'=>'Arial','size'=>10),'Borders'=>PHPExcel_Style_Border::BORDER_THICK,'Fill'=>array('Type'=>PHPExcel_Style_Fill::FILL_SOLID,'Color'=>'0000CDCD')),
		array('Col'=>'E','Width'=>20,'text'=>'Date','font'=>array('Bold'=>true,'Name'=>'Arial','size'=>10),'Borders'=>PHPExcel_Style_Border::BORDER_THICK,'Fill'=>array('Type'=>PHPExcel_Style_Fill::FILL_SOLID,'Color'=>'0000CDCD')),
		array('Col'=>'F','Width'=>50,'text'=>'Description','font'=>array('Bold'=>true,'Name'=>'Arial','size'=>10),'Borders'=>PHPExcel_Style_Border::BORDER_THICK,'Fill'=>array('Type'=>PHPExcel_Style_Fill::FILL_SOLID,'Color'=>'0000CDCD'))
		);
		foreach($column_config as $val){
		$objPHPExcel->getActiveSheet()->getColumnDimension($val['Col'])->setWidth($val['Width']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($val['Col'].$num,$val['text']);
		$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFont()->setBold($val['font']['Bold']);
		$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFont()->setName($val['font']['Name']);
		$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFont()->setSize($val['font']['size']);
		$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getBorders()->getAllBorders()->setBorderStyle($val['Borders']);
		$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFill()->setFillType($val['Fill']['Type']);
        $objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFill()->getStartColor()->setARGB($val['Fill']['Color']);
		}
		$num++;
		foreach ($vefs as $v){
			 $i=1;
			 $description="Verify  according to".$EOF;
			 $description.=$i++.')'.$v->childVersion->document->name.' '.$v->childVersion->name.$EOF;
			 foreach($v->parentVersions as $parent){
			 $description.=$i++.')'.$parent->document->name.' '.$parent->name.$EOF;
			}
			 $objPHPExcel->getActiveSheet()->getRowDimension($num)->setRowHeight(70);
			 $data=array($v->version,$v->author,(string)$v->created_at,$description);
			 $num1=0;
			 foreach($column_config as $val){
			  $objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			  $objPHPExcel->setActiveSheetIndex(0)
                          ->setCellValue($val['Col'].$num,$data[$num1++]);
			  $objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFont()->setName($val['font']['Name']);
			  $objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFont()->setSize($val['font']['size']);
	         
			 } 
			 $num++;
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="report.xls"');
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