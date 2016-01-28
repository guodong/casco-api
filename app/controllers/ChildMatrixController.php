<?php

use Illuminate\Support\Facades\Input;

class ChildMatrixController extends BaseController {

 public function index(){
 	
 	$items=[];
 	//列名与字段一一对应起来吧
 	if($id=Input::get('id')){
 		$items=ChildMatrix::where('verification_id','=',$id)->orderBy('Child Requirement Tag','asc')->get()->toArray();
 	}
   $columModle=array();
   $argu=$items?json_decode($items[0]['column'],true):[];
   foreach($argu as $key=>$value){
   	array_push($columModle,array('dataIndex'=>$key,'header'=>$key,'width'=>140));
   }
   
    $data=[];
    foreach($items as $item){
   	$column=json_decode($item['column'],true);
   	$column=array_merge((array)$item,$column);
   	array_push($data,$column);
   	
   }
    
   return  array('columModle'=>$columModle,'data'=>$data);
 }
 
 
 
 public function  store(){
 	
 	
 	
 }
 
 
 
 public function  show($id){
 	
 	
 	
 	
 	
 }
 
 
 public function update($id){
 	
 	$childs=ChildMatrix::find($id);
 	$childs->update(Input::get());
 	$childs->save();
 	return $childs;
 	
 
 }
 
 public function  filter($array,$key){
 	
 	if(is_array($array)){
 	return array_key_exists($key,$array)?$array[$key]:'';
 	}else if(is_object($array)){
 	return property_exists($array,$key)?$array->$key:'';
 	}else{
 		return '';
 	}
 	
 }
 
 public function export(){
  
 		$ver = Verification::find(Input::get("v_id"));
 		//$ver=Verification::where('id','=',Input::get('v_id'))->first();
		$child_matrix =$ver->childMatrix;
		//var_dump(json_decode($child_matrix[0]->column));exit;
		$column=array();
		foreach(json_decode($child_matrix[0]->column) as $key=>$value){
	    $column[]=$key;
		}
		$user = User::find(Session::get('uid'));
		include PATH_BASE . '/PE/Classes/PHPExcel.php';
		include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()
							 ->setTitle('New_Casco_Parent Matrix')
							 ->setSubject('PHPExcel Test Document')
							 ->setDescription('Test document for PHPExcel, generated using PHP classes.')
							 ->setKeywords('office PHPExcel php')
							 ->setCategory('Test result file');
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$array_config=array('A'=>20,'B'=>20,'C'=>20,'D'=>20);$line_num=5;
		foreach($array_config as $key=>$config){
		$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($config);
		}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line_num, 'Child Requirement Tag');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line_num, 'Child Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line_num, 'Parent Requirement Tag');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line_num, 'Parent Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_num, 'Traceability');//注意头部多选
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line_num, 'No compliance description');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line_num, 'Already described in completeness');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line_num,'Verif_Assessment');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $line_num,'Verif. opinion justification');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$line_num,'CR');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$line_num,'Comment');
		$i=10;
		foreach($column as $value){
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(++$i, $line_num,$value.COL_PREFIX);
	    //设置自定义列宽度
	    $objPHPExcel->getActiveSheet()->getColumnDimension(chr($i+ord('A')))->setWidth(20);
		}
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setSize(10);
		//设置过滤
		$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line_num.':'.chr($i+ord('A')).$line_num);
		
		$row = 1+$line_num;
		foreach ($child_matrix as $item){
			$item=json_decode($item,true);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $this->filter($item,'Child Requirement Tag'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $this->filter($item,'Child Requirement Text'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $this->filter($item,'Parent Requirement Tag'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $this->filter($item,'Parent Requirement Text'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $this->filter($item,'Traceability'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $this->filter($item,'No compliance description'));	
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->filter($item,'Already described in completeness'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $this->filter($item,'Verif_Assessment'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $this->filter($item,'Verif. opinion justification'));
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row,$this->filter($item,'CR'));
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row,$this->filter($item,'Comment'));
			$j=10;
			foreach($column as $key){
	    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(++$j, $row,$this->filter(json_decode($item['column']),$key));
			}
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getFont()->setSize(10);
			$row++;
		}
		 
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="child_matrix.xls"');
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



?>