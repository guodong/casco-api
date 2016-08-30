<?php
use Illuminate\Support\Facades\Input;
// use Illuminate\Support\Facades\Session;
// use Rhumsaa\Uuid\Uuid;

class VatExportController extends BaseController {
    
    public function  __construct(){
        include PATH_BASE . '/PE/Classes/PHPExcel.php';
        include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
        $this->objPHPExcel = new PHPExcel();
    }
    
    public function __destruct() {
        unset($this->objPHPExcel);
    }
    
	public function get_assigned($vat,$active_sheet=0){
	    $objPHPExcel=$this->objPHPExcel;
	    $objPHPExcel->getProperties()
	    ->setTitle('其他阶段分配给本阶段验证需求')
	    ->setSubject('PHPExcel Test Document')
	    ->setDescription('Test document for PHPExcel, generated using PHP classes.')
	    ->setKeywords('office PHPExcel php')
	    ->setCategory('assigned vats');
	    $objPHPExcel->createSheet();
	    $objPHPExcel->setActiveSheetIndex($active_sheet);
	    $objPHPExcel->getActiveSheet()->setTitle($vat[0]['rs_doc_name']); //sheet页命名
	
	    $array_config=array('A'=>20,'B'=>20,'C'=>20,'D'=>40,'E'=>40,'F'=>20,'G'=>20);$line_num=2;
	    foreach($array_config as $key=>$config){
	        $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($config);
	    }
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line_num, 'Req Tag');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line_num, 'Req Doc');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line_num, 'Req Version');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line_num, 'Assigned Vat');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_num,'Vat Comment');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line_num,'Vat Doc');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line_num,'Vat Version');
	    $i=6;
	    $objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setBold(true);
	    $objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setName('Arial');
	    $objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setSize(10);
	    $objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	    $objPHPExcel->getActiveSheet()->getStyle('D1:E'.count($vat))->getAlignment()->setWrapText(true); //设置单元格换行
	
	    //设置过滤
	    $objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line_num.':'.chr($i+ord('A')).$line_num);
	    $row = 1+$line_num;
	
	    foreach ($vat as $item){
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $item['rs_tag_name']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $item['rs_doc_name']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $item['rs_version_name']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $item['vat_tag_name']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $item['vat_comment']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $item['vat_doc_name']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $item['vat_version_name']);
	        $row++;
	    }
	    return  $objPHPExcel;
	}
	
	public function get_assign($vat,$active_sheet=0){
	    $objPHPExcel=$this->objPHPExcel;
	    $objPHPExcel->getProperties()
	    ->setTitle('本阶段分配给其他阶段的需求')
	    ->setSubject('PHPExcel Test Document')
	    ->setDescription('Test document for PHPExcel, generated using PHP classes.')
	    ->setKeywords('office PHPExcel php')
	    ->setCategory('assign vats');
	    $objPHPExcel->createSheet();
	    $objPHPExcel->setActiveSheetIndex($active_sheet);
        $objPHPExcel->getActiveSheet()->setTitle($vat[0]['rs_doc_name']); //sheet页命名
	
	    $array_config=array('A'=>20,'B'=>20,'C'=>20,'D'=>40,'E'=>40);$line_num=2;
	    foreach($array_config as $key=>$config){
	        $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($config);
	    }
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line_num, 'Parent Tag');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line_num, 'Parent Doc');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line_num, 'Parent Version');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line_num, 'Vat');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_num,'Vat comment');
	    $i=4;
	    $objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setBold(true);
	    $objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setName('Arial');
	    $objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setSize(10);
	    $objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	    $objPHPExcel->getActiveSheet()->getStyle('D1:E'.count($vat))->getAlignment()->setWrapText(true); //设置单元格换行
	    
	    //设置过滤
	    $objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line_num.':'.chr($i+ord('A')).$line_num);
	    $row = 1+$line_num;
	
	    foreach ($vat as $item){
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $item['rs_tag_name']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $item['rs_doc_name']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $item['rs_version_name']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $item['rs_vat']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $item['rs_vat_comment']);
	        $row++;
	    }
	    return  $objPHPExcel;
	}
	
	public function get_all($vat){
		$i=0;
		$vat_tc=$vat['vat_tc'];
		$parent_vat=$vat['parent_vat'];
		foreach($vat_tc as $vat_tc_i){
		    $this->get_assigned($vat_tc_i,$i);
		    $i++;
		}
		foreach($parent_vat as $parent_vat_i){
		    $this->get_assign($parent_vat_i,$i);
		    $i++;
		}
		return  $this->objPHPExcel;

	}
}//class




?>