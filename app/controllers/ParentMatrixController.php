<?php

use Illuminate\Support\Facades\Input;

class ParentMatrixController extends BaseController {

 public function index(){
 	
 	$items=[];
 	//列名与字段一一对应起来吧
 	if($id=Input::get('id')){
 		$items=ParentMatrix::where('verification_id','=',$id)->orderBy('Parent Requirement Tag','asc')->get()->toArray();
 	}
   $columModle=array();
   foreach(json_decode($items[0]['column'],true) as $key=>$value){
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
    
 	$parents=ParentMatrix::find($id);
 	$parents->update(Input::get());
 	$parents->save();
 	return $parents;

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
		$parent_matrix =$ver->parentMatrix;
		//var_dump(json_decode($child_matrix[0]->column));exit;
		$column=array();
		foreach(json_decode($parent_matrix[0]->column) as $key=>$value){
	    $column[]=$key;
		}
		include PATH_BASE . '/PE/Classes/PHPExcel.php';
		include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$array_config=array('A'=>20,'B'=>20,'C'=>20,'D'=>20);
		foreach($array_config as $key=>$config){
		$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($config);
		}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Parent Requirement Tag');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Parent Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Child Requirement Tag');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Child Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'justification');//注意头部多选
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Completeness');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'No Compliance Description');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1,'Defect Type');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1,'Verif Assest justifiaction');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1,'CR');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1,'Comment');
		$i=10;
		foreach($column as $value){
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(++$i, 1,$value.COL_PREFIX);
	    //设置自定义列宽度
	    $objPHPExcel->getActiveSheet()->getColumnDimension(chr($i+ord('A')))->setWidth(20);
		}
		$row = 2;
		foreach ($parent_matrix as $item){
			$item=json_decode($item,true);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $this->filter($item,'Parent Requirement Tag'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $this->filter($item,'Parent Requirement Text'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $this->filter($item,'Child Requirement Tag'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $this->filter($item,'Child Requirement Text'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $this->filter($item,'justification'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $this->filter($item,'Completeness'));	
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->filter($item,'No Compliance Description'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $this->filter($item,'Defect Type'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $this->filter($item,'Verif Assest justifiaction'));
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row,$this->filter($item,'CR'));
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row,$this->filter($item,'Comment'));
			$j=10;
			foreach($column as $key){
	    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(++$j, $row,$this->filter(json_decode($item['column']),$key));
			}
			$row++;
		}
		 
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="parent_matrix.xls"');
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