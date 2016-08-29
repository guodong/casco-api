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
		foreach($items as  $key=>$item){
			//在此整理一波数组既可以了吧
			$item['parent_type']=='rs'?$parent=Rs::find($item['parent_id']):$parent=Tc::find($item['parent_id']);
// 			$result[$item['Parent Requirement Tag']]['Parent Requirement Tag']=$item['Parent Requirement Tag'];
			$items[$key]['Parent Requirement Text']=$parent?$parent->description():null;
			$items[$key]['vat']=$parent->vat_json;$vat_result=[];
			foreach((array)json_decode($parent->vat_json,true) as $item){
			 if($item['type']=='vat'){
			 	array_push($vat_result,null);
			 }else{
			 	$result=Result::where('tc_id',$item['id'])->orderBy('created_at','desc')->first();
			 	array_push($vat_result,$result?($result->result.':'.$result->testjob->name.'-'.$result->testjob->build->name):null);
			 }
			}
			$items[$key]['vat_result']=json_encode($vat_result);
		}
		return  $items;
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
