<?php
use Illuminate\Support\Facades\Input;


class ExportReportController extends BaseController {


	public function  __construct(){
		include PATH_BASE . '/PE/Classes/PHPExcel.php';
		include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
		$this->objPHPExcel = new PHPExcel();
	}
    
	public  $array=array(0=>'untested',1=>'passed',2=>'failed');

	public function __destruct() {
		unset($this->objPHPExcel);
	}

	public function   export_testing($report,$active_sheet=0){
		$objPHPExcel=$this->objPHPExcel;
		$circle =array('col'=>'C','row' => 4);
		$config = array(0=>'Test Case ID',1=>'Test Case Description',2=>'Pass/Fail',3=>'Version Tested');
		$testjob=$report->get_results();
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($active_sheet);
	 foreach ($config as $key => $val) {

	 	$objPHPExcel->getActiveSheet()->getColumnDimension(chr(ord($circle['col'])+$key))->setWidth(20);
	 	$objPHPExcel->setActiveSheetIndex($active_sheet)->setCellValue(chr(ord($circle['col']) + $key) . $circle['row'], $val);
	 	$objPHPExcel->getActiveSheet()
	 	->getStyle(chr(ord($circle['col'])+$key) . $circle['row'])
	 	->getBorders()
	 	->getAllBorders()
	 	->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	 	$objPHPExcel->getActiveSheet()
	 	->getStyle(chr(ord($circle['col'])+$key) . $circle['row'])
	 	->getFont()
	 	->setName('Arial');
	 	$objPHPExcel->getActiveSheet()
	 	->getStyle(chr(ord($circle['col'])+$key) . $circle['row'])
	 	->getFont()
	 	->setBold(true);
	 	$objPHPExcel->getActiveSheet()
	 	->getStyle(chr(ord($circle['col'])+$key) . $circle['row'])
	 	->getFont()
	 	->setSize(15);
	 }
	 $num=$circle['row']+1;
		foreach($testjob  as  $flag=>$value){
			$j=0;
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$value['tag']);
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$value['description']);
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$this->array[$value['result']]);
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$value['build']);
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$num++;
		}
		return  $objPHPExcel;
	}



	public function export_cover($report,$active_sheet=0){
			
		$objPHPExcel=$this->objPHPExcel;
		$items=$report->covers;
		$column=[];
		$objPHPExcel->getProperties()
		->setTitle('Requirement Cover Status')
		->setSubject('PHPExcel Test Document')
		->setDescription('Test document for PHPExcel, generated using PHP classes.')
		->setKeywords('office PHPExcel php')
		->setCategory('Test result file');
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($active_sheet);
		//添加title进去
		$circle=array('col'=>'A','row'=>3);
		//$objPHPExcel->setActiveSheetIndex($active_sheet)->setCellValue($circle['col'] . ($circle['row']), $child_doc_name.' COVERS '.$parent_doc_name);
		$objPHPExcel->getActiveSheet()
		->getColumnDimension($circle['col'])
		->setWidth(30);
		$objPHPExcel->getActiveSheet()
		->getStyle($circle['col'] . ($circle['row'] ))
		->getFont()
		->setName('Arial');
		$objPHPExcel->getActiveSheet()
		->getStyle($circle['col'] . ($circle['row']))
		->getFont()
		->setSize(10);

		$array_config=array('A'=>20,'B'=>36,'C'=>20,'D'=>20);$line_num=5;
		foreach($array_config as $key=>$config){
			$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($config);
		}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line_num, 'Parent Requirement Tag');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line_num, 'Parent Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line_num, 'Child Requirement Tag');
		//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line_num, 'Child Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line_num, '通过/失败/未执行/未覆盖/可接受');//注意头部多选
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_num,'result');//Comments');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line_num,'Justification');//Justification');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line_num,'Allocation');// 'Allocation');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line_num,'Comments');//Comments');
		$i=7;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		//设置过滤
		$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line_num.':'.chr($i+ord('A')).$line_num);
		$row = 1+$line_num;
		
		
		$result=[];
		foreach($items as  $key=>$item){
			//在此整理一波数组既可以了吧
			$item['child_type']=='rs'?$child=Rs::find($item['child_id']):$child=Tc::find($item['child_id']);
			$item['parent_type']=='rs'?$parent=Rs::find($item['parent_id']):$parent=Tc::find($item['parent_id']);
			if(!array_key_exists($item['Parent Requirement Tag'],$result)){
			$result[$item['Parent Requirement Tag']]['Parent Requirement Tag']=$item['Parent Requirement Tag'];
			$result[$item['Parent Requirement Tag']]['Parent Requirement Text']=$parent?$parent->description():null;
			$result[$item['Parent Requirement Tag']]['result']=($a=Result::find($item['result_id']))?$a->result:0;
			$result[$item['Parent Requirement Tag']]['common'][]=array('id'=>$item['id'],'comment'=>$item['comment'],'result'=>$result[$item['Parent Requirement Tag']]['result']
			,'allocation'=>$parent->vat_json,'Child Requirement Tag'=>$item['Child Requirement Tag'],'justification'=>$item['justification']);
			}else{
			$m=($a=Result::find($item['result_id']))?$a->result:0;
			$result[$item['Parent Requirement Tag']]['result']=($m)*$result[$item['Parent Requirement Tag']]['result'];
			array_push($result[$item['Parent Requirement Tag']]['common'],array('id'=>$item['id'],'comment'=>$item['comment'],'result'=>$m
			,'allocation'=>$parent->vat_json,'Child Requirement Tag'=>$item['Child Requirement Tag'],'justification'=>$item['justification']));
			}
		}//foreach
		
		foreach ($result as $item){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $item['Parent Requirement Tag']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $item['Parent Requirement Text']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $item['result']);
			$count=count($item['common'])-1;
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.":".'A'.($row+$count));
			$objPHPExcel->getActiveSheet()->mergeCells('B'.$row.":".'B'.($row+$count));
			$objPHPExcel->getActiveSheet()->mergeCells('C'.$row.":".'C'.($row+$count));
			//依次进行赋值即可
			foreach($item['common'] as $common){
				$current=$row++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $current, $common['Child Requirement Tag']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $current, $common['result']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $current, $common['justification']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $current, $common['allocation']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $current, $common['comment']);
			}
			//  $row++;
			
			//$j=8; $data=[];
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getFont()->setName('Arial');
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getFont()->setSize(10);
			
		}

		return  $objPHPExcel;

	}


	public function  verify($verify,$version,$active_sheet=0){

		if(!$verify)return [];
		$objPHPExcel=$this->objPHPExcel;
		$circle =array('col'=>'C','row' => 4);
		$config = array(0=>'Req ID',1=>'Text',2=>'Result',3=>'Test Case ID',4=>'Comment');
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($active_sheet);
		$objPHPExcel->getActiveSheet()->setTitle($version->document->name.'_'.$version->name);
	 foreach ($config as $key => $val) {

	 	$objPHPExcel->getActiveSheet()->getColumnDimension(chr(ord($circle['col'])+$key))->setWidth(20);
	 	$objPHPExcel->setActiveSheetIndex($active_sheet)->setCellValue(chr(ord($circle['col']) + $key) . $circle['row'], $val);
	 	$objPHPExcel->getActiveSheet()
	 	->getStyle(chr(ord($circle['col'])+$key) . $circle['row'])
	 	->getBorders()
	 	->getAllBorders()
	 	->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	 	$objPHPExcel->getActiveSheet()
	 	->getStyle(chr(ord($circle['col'])+$key) . $circle['row'])
	 	->getFont()
	 	->setName('Arial');
	 	$objPHPExcel->getActiveSheet()
	 	->getStyle(chr(ord($circle['col'])+$key) . $circle['row'])
	 	->getFont()
	 	->setBold(true);
	 	$objPHPExcel->getActiveSheet()
	 	->getStyle(chr(ord($circle['col'])+$key) . $circle['row'])
	 	->getFont()
	 	->setSize(15);
	 }
	 $num=$circle['row']+1;
		foreach($verify  as  $flag=>$value){
			$j=0;
			$array=(array)explode(',',$value->tc_id);
			$ans=Tc::whereIn('id',$array)->select('tag')->get()->toArray();
			$test_case=implode(',',$this->array_column($ans,'tag'));
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,Rs::find($value->rs_id)->tag);
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,Rs::find($value->rs_id)->column_text());
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$this->array[$value['result']]);
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$test_case);
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$value['comment']);
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$num++;
		}
		return  $objPHPExcel;

	}


	public function  filter($array,$key){

		if(is_object($array)){
			return property_exists($array,$key)?$array->$key:'';
		}else{
			return '';
		}

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

	public function export_all_sheet($report){
		if(!$report) return [];
		$i=0;
		$vat=$report->testjob->vatbuild;
		foreach($vat->rsVersions as $version){
		 $verify=ReportVerify::where('report_id',$report->id)->where('doc_id',$version->id)->get();
		 //判断是否有数据啊
		 if(count($verify)>0)$this->verify($verify,$version,$i++);
		 else continue;
		}
		$this->export_cover($report,$i++);
		$this->export_testing($report,$i++);
		return  $this->objPHPExcel;
	}

}
?>
