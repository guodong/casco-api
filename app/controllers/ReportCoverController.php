<?php


use Illuminate\Support\Facades\Input;

class ReportCoverController extends ExportReportController {

	public function index(){
		$items=[];
		//列名与字段一一对应起来吧
		if($id=Input::get('report_id')){
			$items=ReportCover::where('report_id','=',$id)->orderBy('Parent Requirement Tag','asc')->get()->toArray();
		}else{
			return [];
		}

		//列名用version的吧
		$data=[];
		foreach($items as  $key=>$item){
			$item['child_type']=='rs'?$child=Rs::find($item['child_id']):$child=Tc::find($item['child_id']);
			$item['parent_type']=='rs'?$parent=Rs::find($item['parent_id']):$parent=Tc::find($item['parent_id']);
			$items[$key]['Child Requirement Text']=$child?$child->description():null;
			$items[$key]['Parent Requirement Text']=$parent?$parent->description():null;
			$items[$key]['result']=Result::find($item['result_id'])->result;
			$items[$key]['allocation']=$parent->vat_json;
		}//foreach
		return  $items;
	}


	public function  update($id){
		$cover=ReportCover::find($id);
		if(!$cover)return [];
		$cover->update(Input::get());
		return $cover;
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
