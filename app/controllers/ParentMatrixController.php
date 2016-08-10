<?php

use Illuminate\Support\Facades\Input;

class ParentMatrixController extends ExportController {

	public function index(){

		//列名与字段一一对应起来吧
		if(($id=Input::get('id'))&&($parent_v_id=Input::get('parent_v_id'))){
			$items=ParentMatrix::where('verification_id','=',$id)->where('parent_v_id','=',$parent_v_id)->orderBy('Parent Requirement Tag','asc')->get();
		}else {return [];}
		//列名用version的吧
		$columModle=[];
		$value=Version::find($parent_v_id);
		$array=explode(',',$value->headers);
		foreach($array as $val){
			global $black;
			if(in_array($val,$black))continue;
			$tmp=array('dataIndex'=>$val,'header'=>$val,'width'=>140);
			!in_array($tmp,$columModle)?array_push($columModle,$tmp):null;
		}
		$data=[];
		foreach($items as $item){
			$da=[];
			foreach($item->toArray()  as  $k=>$v){
				$da[$k]=$v;
			}
			$item->child_type=='rs'?$child=Rs::find($item->child_id):$child=Tc::find($item->child_id);
			$item->parent_type=='rs'?$parent=Rs::find($item->parent_id):$parent=Tc::find($item->parent_id);
			$child_column=$child?$child->column():[];
			$item->child_type=='tc'?($da['justification']=$parent?$parent->vat_json:[]):($da['justification']=$item->justification);
			//if(preg_match('/-0011/',$parent->tag)){var_dump($parent->column());exit();}
			$da['Child Requirement Text']=$child?$child->description():null;$da['Parent Requirement Text']=$parent?$parent->description():null;
			foreach($column=(array)$parent->column() as $key=>$val){
				switch($key){
					case 'contribution':
						array_key_exists('safety',(array)$child_column)?$da[$key]=$val.MID_COMPOSE.$child_column['safety']:$da[$key]=$val.MID_COMPOSE;
						break;
					default:
						array_key_exists($key,(array)$child_column)?$da[$key]=$val.MID_COMPOSE.$child_column[$key]
						:$da[$key]=$val.MID_COMPOSE;
				}//switch
			}//foreach
			$data[]=$da;
		}//foreach

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