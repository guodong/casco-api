<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class TestjobController extends BaseController{

	public function show($id)
	{
		return Tc::find($id);
	}

	public function index()
	{
	    $jobs = Project::find(Input::get('project_id'))->testjobs;
	    foreach ($jobs as $v){
	        $v->build;
	        $v->tcVersion->document;
	        foreach ($v->rsVersions as $vv){
	            $vv->document;
	        }
	    }
	    return $jobs;
	}
	
	public function store()
	{
	    $job = Testjob::create(Input::get());
	    $job->status = 0;
	    foreach (Input::get('rs_versions') as $v){
	        $job->rsVersions()->attach($v['rs_version_id']);
	    }
	    $v = $job;
	    $v->build;
	    $v->tcVersion->document;
	    foreach ($v->rsVersions as $vv){
	        $vv->document;
	    }
	    foreach (Input::get('tcs') as $tcid){
	        Result::create(array(
	            'tc_id' => $tcid,
	            'testjob_id' => $job->id
	        ));
	    }
	    return $job;
	}
	
	public function update()
	{
	    $t = Testjob::find(Input::get('id'));
	    $t->update(Input::get());
	    return $t;
	}
	
	public function rsversion()
	{
	    $job = Testjob::find(Input::get('job_id'));
	    $rsvs = $job->rsVersions;
	}
	
	public function export()
	{
	    $job = Testjob::find(Input::get("job_id"));
	    $results = $job->results;
	    $user = User::find(Session::get('uid'));
	    
	    include PATH_BASE . '/PE/Classes/PHPExcel.php';
	    include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
	    $objPHPExcel = new PHPExcel();
	    $objPHPExcel->setActiveSheetIndex(0);
	    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('20');
	    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('20');
	    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('20');
	     

	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Tc tag');

	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Description');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Tester');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Begin at');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'End at');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Result');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'Comment');
	    $row = 2;
	    foreach ($results as $v){
	        $tc = $v->tc;
	        $startrow = $row;
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $tc->tag);
	        $item=json_decode('{'.$tc->column.'}',true);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, array_key_exists('description',$item)?$item['description']:$item['test case description']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $user->realname);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, substr($v->begin_at, 0, 1)=='0'?'':$v->begin_at);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, substr($v->end_at, 0, 1)=='0'?'':$v->end_at);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $v->result == 0?'untested':($v->result == 1?'passed':'failed'));
	        $cod = $row;
	        $step_count = 0;
	        
	        foreach ($tc->steps as $step){
	        	  
	        	  $id=json_decode($step->toJson())->id;
	            $stepResult = ResultStep::where('result_id', $v->id)->where('step_id',$id)->first();
	            
	            if(!$stepResult)continue;
	           // echo ($stepResult->step_id);
	            $r = $stepResult->result == 0?'untested':($stepResult->result == 1?'passed':'failed');
	            if ($stepResult->comment){
	               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $cod++, '#'.$step->num . ' ' . $r . ': ' . $stepResult->comment);
	               $step_count++;
	            }
	        }
           /*
           $stepResult = ResultStep::where('result_id', $v->id)->get();
           foreach($stepResult as $value){
           $r = $value->result == 0?'untested':($value->result == 1?'passed':'failed');
           if($value->comment){
           	
           	  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $cod++, '#'.$->num . ' ' . $r . ': ' . $value->comment);
           	  $step_count++;
           	
          }
          }
          */


	        if ($step_count>1){
	            $row += $step_count-1;
	        }
	        $endrow = $row;

	        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $startrow, 0, $endrow);
	        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(1, $startrow, 1, $endrow);
	        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(2, $startrow, 2, $endrow);
	        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $startrow, 3, $endrow);
	        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(4, $startrow, 4, $endrow);
	        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(5, $startrow, 5, $endrow);
	        $row++;
	    }
	    
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="output.xls"');
	    header('Cache-Control: max-age=0');
	    header('Cache-Control: max-age=1');
	    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always
	    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	    header('Pragma: public'); // HTTP/1.0
	    
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	    $objWriter->save('php://output');
	}
	
}