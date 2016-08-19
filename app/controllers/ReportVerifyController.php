<?php

use Illuminate\Support\Facades\Input;
class ReportVerifyController extends  ExportReportController{

	public function index()
	{
		$datas=[];
		if(Input::get('report_id')&&Input::get('doc_id')){
			$verify =ReportVerify::where('report_id', '=', Input::get('report_id'))->where('doc_id',Input::get('doc_id'))->get();
		}else if(Input::get('report_id')){
			$verify =ReportVerify::where('report_id', '=', Input::get('report_id'))->get();
		}
		if(!$verify) return $datas;
		foreach($verify  as $ver){
			$array=array_keys($ver);$values=array_values($ver);$result=1;
			foreach($values as $value){
				if($value)
				$result*=(Result::find($value)->result);
				else
				$result*=0;
			}
			$ans=Tc::whereIn('id',$array)->select('tag')->get()->toArray();
			$ver['test_case']=implode(',',$this->array_column($ans,'tag'));
			$ver['tag']=Rs::find($ver->rs_id)->tag;
			//重新进行计算得出值罢
			$ver['result']=$result;
			$ver['description']=$text=Rs::find($ver->rs_id)->column_text();

		}//遍历$verify
		return $verify;
	}

	public function show($id)
	{
		return 	 ReportVerify::find($id);
	}

	public function store()
	{
		$build = Build::create(Input::get());
		return $build;
	}

	public function  update($id){

		$verify=ReportVerify::find($id);
		$verify->update(Input::get());
		return $verify;

	}


	public function  destroy($id)
	{
		$build=ReportVerify::find($id);
		$build->destroy($id);
		return  $build;

	}
	 
	public  function export_verify(){
		 
		 
		if(Input::get('doc_id')&&Input::get('report_id')){
		$verify=ReportVerify::where('report_id',Input::get('report_id'))->where('doc_id',Input::get('doc_id'))->get();
		$version=Version::find(Input::get('doc_id'));
		}
		else if(Input::get('report_id'))
		$verify=ReportVerify::where('report_id',Input::get('report_id'))->get();
		else $verify=[];
		$active_sheet=0;
		$objPHPExcel=parent::verify($verify,$version,$active_sheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="其他阶段分配给本阶段的需求.xls"');
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



}
