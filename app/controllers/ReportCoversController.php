<?php


use Illuminate\Support\Facades\Input;

class ReportCoversController extends ExportReportController {

	public function index(){
		$items=[];
		//列名与字段一一对应起来吧
		if($id=Input::get('p_id')){
			$items=ReportMiddle::where('p_id','=',$id)->distinct()->get()->toArray();
		}else{
			return [];
		}
		$data=[];$result=[];
		foreach($items as  $key=>$item){
			//在此整理一波数组既可以了吧
			$item['child_type']=='rs'?$child=Rs::find($item['child_id']):$child=Tc::find($item['child_id']);
			$items[$key]['Child Requirement Tag']=$child?$child->tag:null;
			$items[$key]['Child Requirement Text']=$child?$child->description():null;
			$items[$key]['result']=($a=Result::find($item['result_id']))?$a->result:0;
			$items[$key]['comment']=$item['comment'];
		}//foreach
		return  array_values($items);
	}

	
	public  function show($id){
		
		$ids=explode(',',$id);
		if($ids){
			$items=ReportCover::whereIn('id',$ids)->orderBy('Parent Requirement Tag','asc')->get()->toArray();
		}else{
			return [];
		}
		foreach($items as  $key=>$item){
			//在此整理一波数组既可以了吧
			$item['child_type']=='rs'?$child=Rs::find($item['child_id']):$child=Tc::find($item['child_id']);
			$item['parent_type']=='rs'?$parent=Rs::find($item['parent_id']):$parent=Tc::find($item['parent_id']);
			$result[$item['Parent Requirement Tag']]['Parent Requirement Tag']=$item['Parent Requirement Tag'];
			$items[$key]['Child Requirement Text']=$child?$child->description():null;
			$items[$key]['Parent Requirement Text']=$parent?$parent->description():null;
			$items[$key]['result']=($a=Result::find($item['result_id']))?$a->result:0;
			$items[$key]['allocation']=$parent->vat_json;
		}//foreach
		return  $items;
		
	}
	public function  update($id){
		$cover=ReportMiddle::find($id);
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
