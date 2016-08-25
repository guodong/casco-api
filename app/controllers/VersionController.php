<?php

use Illuminate\Support\Facades\Input;
class VersionController extends ExportController {

	public function index()
	{
		if(Input::get('newest') && Input::get('document_id')){
		    $versions=Version::where('document_id','=',Input::get('document_id'))->orderBy('updated_at','desc')->first();
		    $versions&&$versions->document;
		}else if(Input::get('new_update')&&Input::get('document_id')){
		  $versions =Version::where('document_id', '=', Input::get('document_id'))->orderBy('updated_at', 'desc')->first();
		  $versions&&$versions->document;
		  }else if(Input::get('document_id')){
		      $versions =Version::where('document_id', '=', Input::get('document_id'))->orderBy('created_at', 'desc')->get();
		      foreach($versions as $vers){
			     $vers->document;
		      }
		}
		
		return $versions;
	}



	public function store()
	{  
		$data=Input::all();
	   if(Input::get('document')){$data['document_id']=Input::get('document')['id'];}
		$version = Version::create($data);
		return $version;
	}

	public function update($id)
	{
		$version=Version::find($id);
		$data=Input::all();
		$version->fill($data);
		$version->save();
		return $version;
	}
	public function  export(){
		$ver=Version::find(Input::get('id'));
		if(!$ver)return [];
		$active_sheet=0;
		$objPHPExcel=parent::result_export($ver,$active_sheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="导出结果.xls"');
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
	public function show($id)   //在线浏览文档
	{
		$version = Version::find($id);
		return $version;
	}
	public function  destroy($id)
	{
		$vs=Version::find($id);
		foreach($vs->tcs as $tcs){
			$tcs->delete();
		}
		foreach($vs->rss as $rss){
			$rss->delete();
		}
		Version::destroy($id);//删除versions
		return $vs;
	}
}
