<?php

use Illuminate\Support\Facades\Input;
class TcController extends Controller{

	public function show($id)
	{
		return Tc::find($id);
	}

	public function index()
	{
	    $tcs = Tc::where('document_id', '=', $_GET['document_id'])->get();
        
        $tcs->each(function($tc){
            $tc->steps;
            $tc->sources;
        });
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
	
	public function update($id)
	{
	    $m = Tc::find($id);
	    $m->update(Input::get());
	    $m->sources()->detach();
	    foreach (Input::get('sources') as $v){
	        $m->sources()->attach($v['id']);
	    }
	    $m->steps()->delete();
	    foreach (Input::get('steps') as $v){
	        $step = new TcStep($v);
	        $m->steps()->save($step);
	    }
	    return $m;
	}
	
	public function export ()
	{
	    $doc = Document::find(Input::get("document_id"));
	
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
	    foreach ($doc->tcs as $tc){
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
