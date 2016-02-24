<?php

use Illuminate\Support\Facades\Input;

class ChildMatrixController extends ExportController {

	public function index(){

		$items=[];
		//列名与字段一一对应起来吧
		if($id=Input::get('id')){
			$items=ChildMatrix::where('verification_id','=',$id)->orderBy('Child Requirement Tag','asc')->get()->toArray();
		}
		$columModle=array();
		$argu=$items?json_decode($items[0]['column'],true):[];
		foreach((array)$argu as $key=>$value){
			$val_key=key((array)$value);
			array_push($columModle,array('dataIndex'=>$val_key,'header'=>$val_key,'width'=>140));
		}
		$data=[];
		foreach($items as $item){
			$column=(array)json_decode($item['column'],true);
			$inner=[];
			foreach($column as  $val){
				$inner=array_merge($inner,$val);
			}
			$column=array_merge((array)$item,(array)$inner);
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

		$objPHPExcel=parent::export_childs(Input::get('v_id'),0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="child_matrix.xls"');
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



?>