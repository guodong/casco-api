<?php
use Illuminate\Support\Facades\Input;


class ExportReportController extends BaseController {


	public function  __construct(){
		include PATH_BASE . '/PE/Classes/PHPExcel.php';
		include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
		$this->objPHPExcel = new PHPExcel();
	}


	public function __destruct() {
		unset($this->objPHPExcel);
	}

	public function   export_testing($report,$active_sheet=1){
		$objPHPExcel=$this->objPHPExcel;
		//$objPHPExcel->createSheet();
		$circle =array('col'=>'C','row' => 4);
		$config = array(0=>'Test Case ID',1=>'Test Case Description',2=>'Pass/Fail',3=>'Version Tested');
		$testjob=$report->get_results();
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
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$value['result']);
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$value['build']);
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getFont()->setSize(10);
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line_num, 'Child Requirement Tag');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line_num, 'Child Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_num, '通过/失败/未执行/未覆盖/可接受');//注意头部多选
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line_num, 'Justification');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line_num, 'Allocation');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line_num,'Comments');
		/*$objValidation = $objPHPExcel->getActiveSheet()->getCell("D".$line_num)->getDataValidation(); //这一句为要设置数据有效性的单元格
		 $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		 -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		 -> setAllowBlank(true)
		 -> setShowInputMessage(true)
		 -> setShowErrorMessage(true)
		 -> setShowDropDown(true)
		 -> setPromptTitle('设备类型')
		 -> setFormula1('"列表项1,列表项2,列表项3"');
		 */
		$i=8;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		//设置过滤
		$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line_num.':'.chr($i+ord('A')).$line_num);
		$row = 1+$line_num;
		foreach ($items as $item){
			//var_dump($item);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $item['Parent Requirement Tag']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $item->parents->description());
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $item['Child Requirement Tag']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $item->child->description());
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $item['justification']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $item->result->result);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, Tc::find($item['child_id'])->vat_json);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $item['comment']);
			$j=8; $data=[];
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getFont()->setSize(10);
			$row++;
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





	public function export_all_sheet($project_id,$child_id,$v_id){
		$i=0;
		$this->export_report($project_id,$child_id,$i++);
		$this->summary_exp($v_id,$i++);
		$this->export_childs($v_id,$i++);
		return  $this->objPHPExcel;
	}

}
?>
