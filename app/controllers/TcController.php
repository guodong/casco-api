<?php

use Illuminate\Support\Facades\Input;
class TcController extends Controller{

	public function show($id)
	{
		return Tc::find($id);
	}

	public function index()
	{
	    if (Input::get('version_id')) {
	        $version = Version::find(Input::get('version_id'));
	        $tcs = $version->tcs;
	    }else{
	        $document = Document::find(Input::get('document_id'));
	        $tcv = $document->latest_version();
	        if (!$tcv){ 
	            return '[]';
	        }
	        $tcs = $tcv->tcs;
	    }
	    foreach($tcs as $tc){
            $tc->steps;
            $tc->sources();
            $arr = explode(',',$tc->testmethod_id);
            $tms = [];
            foreach($arr as $v ){
                $tmp = Testmethod::find($v);
                   if($tmp){
                       $tms[] = $tmp;
                   }
            }
            $tc->testmethods = $tms;
            $tc->result = 0;
            foreach ($tc->results as $r){
                if ($r->rs_version_id == Input::get('rs_version_id') && $r->build_id == Input::get('build_id')){
                    $tc->result = $r->result;
                }
            }
        };
        return $tcs;
	}
	
	public function store()
	{
	    $tc = Tc::create(Input::get());
	    foreach (Input::get('sources') as $v){
	        $tc->sources()->attach($v['id']);
	    }
	    
	    foreach (Input::get('steps') as $v){
	        $step = new TcStep($v);
	        $tc->steps()->save($step);
	    }
	    return $tc;
	}
    public function destroy($id){

     $tc=Tc::find($id);
	 $tc=$tc->destroy($id);
	 return $tc;

	}
	 

	
	
	public function setresult()
	{
	    $result = Result::where('tc_id','=',Input::get('tc_id'))->where('rs_version_id','=',Input::get('rs_version_id'))->where('build_id','=',Input::get('build_id'))->first();
	    if (!$result){
	        $result = Result::create(Input::get());
	    }else{
	        $result->result = Input::get('result');
	        $result->save();
	    }
	}
	
	public function update($id)
	{
	    $m = Tc::find($id);
	    $m->update(Input::get());	  
	      $data = Input::get('sources');
	      $arr = [];
	    foreach ($data as $v){
	        $arr[] = $v['tag'];
	    }
	    $m->source_json = json_encode($arr);
	    $m->save();
	    $m->steps()->delete();
	    foreach (Input::get('steps') as $v){
	        $step = new TcStep($v);
	        $m->steps()->save($step);
	    }
	    return $m;
	}
	
	public function export ()
	{
	    $ver = Version::find(Input::get("version_id"));
	
	    include PATH_BASE . '/PE/Classes/PHPExcel.php';
	    include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
	    $objPHPExcel = new PHPExcel();
	    $objPHPExcel->setActiveSheetIndex(0);
	    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
	    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('20');
	    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('40');
	    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('30');
	    $row = 1;
	    foreach ($ver->tcs as $tc){
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row++, $tc->tag);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Test Case Description');
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row++, $tc->description);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Test Method');
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row++, $tc->test_method);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Pre-condition');
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row++, $tc->pre_condition);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Test Steps');
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Actions');
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row++, 'Expected Result');
	        foreach ($tc->steps as $step){
	            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $step->num);
	            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $step->actions);
	            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row++, $step->expected_result);
	        }
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Result');
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row++, $tc->result==0?'untested':($tc->result==1?'passed':'failed'));
	        $row++;$row++;
	    }
	
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="output.xls"');
	    header('Cache-Control: max-age=0');
	    // If you're serving to IE 9, then the following may be needed
	    header('Cache-Control: max-age=1');
	
	    // If you're serving to IE over SSL, then the following may be needed
	    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always
	    // modified
	    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	    header('Pragma: public'); // HTTP/1.0
	
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	    $objWriter->save('php://output');
	}
	
}
