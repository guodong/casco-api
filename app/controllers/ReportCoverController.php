<?php


use Illuminate\Support\Facades\Input;

class ReportCoverController extends ExportReportController {
	
	public function index(){
		$items=[];
		//列名与字段一一对应起来吧
		if($report_id=Input::get('report_id')){
			$items=ReportCover::where('report_id',$report_id)->orderBy('Parent Requirement Tag')->get()->toArray();
		}else{
			return [];
		}
		$array_result=array('0'=>'untested','-1'=>'failed','1'=>'passed');
		foreach($items as  $key=>$item){
			//在此整理一波数组既可以了吧
			$item['parent_type']=='rs'?$parent=Rs::find($item['parent_id']):$parent=Tc::find($item['parent_id']);
			$items[$key]['Parent Requirement Text']=$parent?$parent->description():null;
			//var_dump((array)ReportCover::find($item['id'])->middles);
			//没有result_id表示是额外添加进去的
			$results=1;
			//var_dump(ReportCover::find($item['id'])->middles->toArray());exit;
			foreach(ReportCover::find($item['id'])->middles->toArray() as $good){
				$results*=($good['result_id']?Result::find($good['result_id'])->result:0);
			}
			$vat_result=(array)json_decode($item['vats'],true);//var_dump($vat_result);
			$vat_tag=$this->array_column($vat_result,'tag');
			$vat_id=$this->array_column($vat_result,'id');
			//在show层做一个统一处理
			foreach((array)json_decode($parent->vat_json,true) as $item){
			 if($item['type']=='vat'){
			 	if(!in_array($item['tag'],$vat_tag)){
			 		$item['vat_result']=0;
			 		$item['comment']=null;
			 		$vat_result[]=$item;
			 	}else if(!array_key_exists('vat_result',$item)){
			 		$item['vat_result']=0;
			 	}
			 	//$results*=(array_key_exists('vat_result',$item)?$item['vat_result']:0);
			 	    $results*=$item['vat_result'];
			 }else{
			 	//每次show则重新计算一下
			 	if(!in_array($item['id'],$vat_id)){
			 	$result=Result::where('tc_id',$item['id'])->orderBy('created_at','desc')->first();
			 	$item['vat_result']=$result?($result->result):0;
			 	$item['comment']=null;
			 	$item['v_build']=$result->testjob->name.'-'.$result->testjob->build->name;
			 	$vat_result[]=$item;
			 	}else if(!array_key_exists('vat_result',$item)){
			 		$item['vat_result']=0;
			 	}
			 	//var_dump($item['vat_result']);
			 	$results*=($item['vat_result']);
			 }
			}
			$items[$key]['vats']=json_encode($vat_result);
			$items[$key]['result']=$results;
		}
		return  $items;
	}
	
	
	public function array_column($input,$column_key,$index_key=''){

		if(!is_array($input)) return;
		$results=array();
		if($column_key===null){
			if(!is_string($index_key)&&!is_int($index_key)) return false;
			foreach($input as $_v){
				if(array_key_exists($index_key,$_v)){
					$results[$_v[$index_key]]=$_v;
				}
			}
			if(empty($results)) $results=$input;
		}else if(!is_string($column_key)&&!is_int($column_key)){
			return false;
		}else{
			if(!is_string($index_key)&&!is_int($index_key)) return false;
			if($index_key===''){
				foreach($input as $_v){
					if(is_array($_v)&&array_key_exists($column_key,$_v)){
						$results[]=$_v[$column_key];
					}
				}
			}else{
				foreach($input as $_v){
					if(is_array($_v)&&array_key_exists($column_key,$_v)&&array_key_exists($index_key,$_v)){
						$results[$_v[$index_key]]=$_v[$column_key];
					}
				}
			}

		}
		return $results;
	}
	
	public function post(){
		
		$datas=(array)Input::get('datas');
		if(!$datas)return [];
		foreach($datas as $key=>$value){
		  	//var_dump($value);
			// $value=json_decode($value['value']);
			 $report=ReportCover::find($value['key']);
			 if(!$report)continue;
			 $report->vats=json_encode($value['value']);
			 $report->save();
			 /*foreach($value->value as  $item){
			 	if($item->type='vat'){
			    $parentreport->vats=$item);
			 	}else{
			 		
			 		
			 	}
			 	
			 	
			 }//foreach
			*/
		}
		return  ['success'=>true];
		
		
	}
	public function export(){
		
		if(!Input::get('report_id')||!$report=Report::find(Input::get('report_id')))return [];
		$active_sheet=0;
		$objPHPExcel=parent::export_cover($report,$active_sheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="cover_status.xls"');
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
