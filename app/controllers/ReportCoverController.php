<?php


use Illuminate\Support\Facades\Input;

class ReportCoverController extends ExportReportController {

	public function index(){
		$items=[];
		//列名与字段一一对应起来吧
		if($id=Input::get('report_id')){
			$items=ReportCover::where('report_id','=',$id)->orderBy('Parent Requirement Tag','asc')->distinct()->get()->toArray();
		}else{
			return [];
		}

		//列名用version的吧
		$data=[];$result=[];
		foreach($items as  $key=>$item){
			//在此整理一波数组既可以了吧
			$item['child_type']=='rs'?$child=Rs::find($item['child_id']):$child=Tc::find($item['child_id']);
			$item['parent_type']=='rs'?$parent=Rs::find($item['parent_id']):$parent=Tc::find($item['parent_id']);
			if(!array_key_exists($item['Parent Requirement Tag'],$result)){
			$result[$item['Parent Requirement Tag']]['Parent Requirement Tag']=$item['Parent Requirement Tag'];
			$result[$item['Parent Requirement Tag']]['Parent Requirement Text']=$parent?$parent->description():null;
			//$result_id为空时为增补的tc结果集
			$result[$item['Parent Requirement Tag']]['result']=($a=Result::find($item['result_id']))?$a->result:0;
			$result[$item['Parent Requirement Tag']]['common'][]=$item['id'];
			}else{
			$result[$item['Parent Requirement Tag']]['result']=(($a=Result::find($item['result_id']))?$a->result:0)*$result[$item['Parent Requirement Tag']]['result'];
			$result[$item['Parent Requirement Tag']]['common'][]=$item['id'];
			}
		}//foreach
		return  array_values($result);
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
