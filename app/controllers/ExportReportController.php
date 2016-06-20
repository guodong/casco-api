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
	$testjob=$report->testjob->results;
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
		foreach($testjob  as  $flag=>$tests){
			$j=0;
			$tc=Tc::find($tests->tc_id);
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$tc->tag);
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$tc->description());
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$tc->result);
			$objPHPExcel->setActiveSheetIndex($active_sheet)
			->setCellValue(chr(ord($circle['col'])+$j++).$num,$report->testjob->name.':'.$report->testjob->build->name);
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getStyle($circle['col'].$num.':'.chr(ord($circle['col'])+$j).$num)->getFont()->setSize(10);
			$num++;
		}
            return  $objPHPExcel;
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
