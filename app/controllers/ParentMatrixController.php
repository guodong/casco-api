<?php

use Illuminate\Support\Facades\Input;

class ParentMatrixController extends BaseController {

	public function index(){

		$items=[];
		//列名与字段一一对应起来吧

		if(($id=Input::get('id'))&&($parent_v_id=Input::get('parent_v_id'))){
			$items=ParentMatrix::where('verification_id','=',$id)->where('parent_v_id','=',$parent_v_id)->orderBy('Parent Requirement Tag','asc')->get()->toArray();
		}else if($id=Input::get('id')){
			$items=ParentMatrix::where('verification_id','=',$id)->orderBy('Parent Requirement Tag','asc')->get()->toArray();
		}else {return [];}
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
		 
		if(Input::get('v_id')&&Input::get('parent_v_id')){
			$parent_matrix=ParentMatrix::where('verification_id','=',Input::get('v_id'))
			->where('parent_v_id','=',Input::get('parent_v_id'))->get();
			$ver=Verification::find(Input::get("v_id"));
		}else if(Input::get('v_id')){
			$ver = Verification::find(Input::get("v_id"));
		    $parent_matrix =$ver->parentMatrix;
		}else{
			$ver=[];
			$parent_matrix=[];
		}
		//var_dump($parent_matrix);exit;
		$tmp_col=count($parent_matrix)>0?json_decode($parent_matrix[0]->column):[];
		$column=array();
		foreach($tmp_col as $key=>$value){
			$column[]=$key;
		}
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
		//添加title进去
		$circle=array('col'=>'A','row'=>3);
		$child_doc_name=$ver->childVersion->old_filename;
		$parent_doc_name=count($parent_matrix)>0?$parent_matrix[0]->version->old_filename:'';
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($circle['col'] . ($circle['row']), $child_doc_name.' COVERS '.$parent_doc_name);
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_num, 'justification');//注意头部多选
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line_num, 'Completeness');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line_num, 'No Compliance Description');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line_num,'Defect Type');
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $this->filter($item,'Verif_Assesst'));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $line_num,'Verif Assest justifiaction');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $line_num,'CR');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$line_num,'Comment');
		$i=10;
		foreach($column as $value){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(++$i,$line_num,$value.COL_PREFIX);
			//设置自定义列宽度
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i+ord('A')))->setWidth(20);
		}
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		//设置过滤
		$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line_num.':'.chr($i+ord('A')).$line_num);
		$row = 1+$line_num;
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
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $this->filter($item,'Verif_Assesst'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $this->filter($item,'Verif Assest justifiaction'));
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