<?php

use Illuminate\Support\Facades\Input;

class ParentMatrixController extends ExportController {

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
		foreach((array)$argu as $key=>$value){
			$val_key=key((array)$value);
			array_push($columModle,array('dataIndex'=>$val_key,'header'=>$val_key,'width'=>140));
		}
		$data=[];
		foreach($items as $item){
			$column=(array)json_decode($item['column'],true);
			$inner=[];
			foreach($column as  $val){
                            $inner=array_merge($inner
                            	,$val);
                     	}
			//var_dump($inner);
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
		 
		$objPHPExcel=parent::export_parents(Input::get('v_id'),Input::get('parent_v_id'),0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="parent_matrix.xls"');
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