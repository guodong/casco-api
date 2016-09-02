<?php

use Illuminate\Support\Facades\Input;

class ChildMatrixController extends ExportController {

	public function index(){
		$items=[];
		//列名与字段一一对应起来吧
		if($id=Input::get('id')){
			$items=ChildMatrix::where('verification_id','=',$id)->orderBy('Child Requirement Tag','asc')->get();
		}else{
			return [];
		}
		//列名用version的吧
		$columModle=[];
		foreach(Verification::find($id)->parentVersions as $key=>$value){
			//怎样来做
			$array=explode(',',$value->headers);
			foreach($array as $val){
				global $black;
				if(in_array($val,$black))continue;
				$tmp=array('dataIndex'=>$val,'header'=>$val,'width'=>140);
				!in_array($tmp,$columModle)?array_push($columModle,$tmp):null;
			}
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
			$da['justification']=$parent?$parent->vat_json:[];
			$da['Child Requirement Text']=$child?$child->description():null;$da['Parent Requirement Text']=$parent?$parent->description():null;
			foreach($column=$parent?$parent->column():[] as $key=>$val){
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