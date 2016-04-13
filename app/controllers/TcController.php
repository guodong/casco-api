<?php

use Illuminate\Support\Facades\Input;
class TcController extends Controller{

	public function show($id)
	{
		return Tc::find($id);
	}
	public function  striplashes($item){

		$item=preg_replace("/([\r\n])+/", "", $item);//过滤掉一种奇葩编码,shit!
		$item=str_replace("'","\'",$item);
		return  $item;
	}


	public function tc_steps(){
		 
		if(Input::get('tc_id')){

			$tc=Tc::find(Input::get('tc_id'));
			return $tc?json_encode($tc->steps):null;
			 
		}
		 
		 
	}
	public function index()
	{

		$version= Input::get('document_id')?Document::find(Input::get('document_id'))->latest_version():(Input::get('version_id')?Version::find(Input::get('version_id')):'');
		if (!$version){
			return '[]';
		}
		$tcs = $version->tcs;
		$final=array();
		if(Input::get('act')=="stat"){
			foreach($tcs as $tc){
				$arr = json_decode('{'.$tc->column.'}',true);
				if($arr&&array_key_exists('test method',$arr))
				{
					(count($test_methods=explode('/',$arr['test method']))>1)||
					(count($test_methods=explode('+',$arr['test method']))>1)||
					(count($test_methods=explode('&',$arr['test method']))>1);
						

					$ids=Testmethod::whereIn('name',(array)$test_methods)->get()->toArray();
					$tc->testmethods = $ids;
					$tc->result = 0;}
					foreach ($tc->results as $r){
						if ($r->rs_version_id == Input::get('rs_version_id') && $r->build_id == Input::get('build_id')){
							$tc->result = $r->result;
						}
					}
					$final[]=array('tc'=>$tc);
			}

			return  $final;
		}//if


		$data=array();
		foreach ($tcs as $v){
			$v->column=$this->striplashes($v->column);
			$obj=json_decode('{"id":"'.$v->id.'","tag":"'.$v->tag.($v->column?('",'.$v->column):'"').'}');//票漂亮哦
			if(!$obj)continue;
			$data[]=$obj;
		}
		 
		//还要解析相应的列名，列名也要发送过去么,怎么办?列名怎样规范化处理呢?
		$version = Version::find ( Input::get ( 'version_id' ) );
		$column=explode(",",$version->headers);
		$columModle=array();
		$fieldsNames=array();
		$black_list=array('execution step','expected output','test steps');
		$columModle[]=array('dataIndex'=>'tag','header'=>'tag','width'=> 140);
		$fieldsNames[]=array('name'=>'tag');
		foreach($column as $item){
			in_array($item,$black_list)&&continue;
			$columModle[]=array('dataIndex'=>$item,'header'=>$item,'width'=> 140);
			$fieldsNames[]=array('name'=>$item);

		}
	  
		return  array('columModle'=>$columModle,'data'=>$data,'fieldsNames'=>$fieldsNames);
		/*
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
		 return $tcs;  */


	}

	public function matrix(){

		//默认选取最新的版本么?
		$version=(Input::get('version_id')?Version::find(Input::get('version_id')):Version::orderBy('updated_at','desc')->first());
		if (!$version){
			return '[]';
		}
		$tcs = $version->tcs;
		$final=array();
		$data=array();
		foreach ($tcs as $v){
			$obj=json_decode('{"id":"'.$v->id.'","tag":"'.$v->tag.($v->column?('",'.$v->column):'"').'}');//票漂亮哦
			if(!$obj)continue;
			$data[]=$obj;
		}
		 
		//还要解析相应的列名，列名也要发送过去么,怎么办?列名怎样规范化处理呢?
		$column=explode(",",$version->headers);
		$columModle=array();
		$fieldsNames=array();
		$columModle[]=(array('dataIndex'=>'tag','header'=>'tag','width'=> 140));
		$fieldsNames[]=array('name'=>'tag');
		foreach($column as $item){
			 
			if($item=='test steps')continue;
			$columModle[]=(array('dataIndex'=>$item,'header'=>$item,'width'=> 140));
			$fieldsNames[]=array('name'=>$item);

		}
	  
		return  array('columModle'=>$columModle,'data'=>$data,'fieldsNames'=>$fieldsNames);






	}

	public function store()
	{
		$tc = Tc::create(Input::get());//这种create方式碉堡了 
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

		$m->save();
		if(Input::get('steps')){
			$m->steps()->delete();
			foreach (Input::get('steps') as $v){
				$step = new TcStep($v);
				$m->steps()->save($step);
			}
		}
		return $m;
	}




	public function export ()
	{
		$ver = Version::find(Input::get("version_id"));
		$columns=explode(',',$ver->headers);
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
			$tc->column=json_decode('{'.$tc->column.'}',true);
			if(!$tc->column)continue;
			 
			foreach($columns as $column){
				/*var_dump($column);
				 if(!array_key_exists($column,$tc->column)){
				 continue;
				 }
				 */
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'sdf');
				switch($column){

					case 'description':

						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $column);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row++, $tc->column['description']);//."\r\n".

						break;

					case 'test case description':
						 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $column);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row++, $tc->column['test case description']);//."\r\n".
						 
						break;
						 
					case  'test steps':
						 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'test steps');
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'actions');
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row++, 'expected result');
						foreach ($tc->steps as $step){
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $step['num']);
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $step['actions']);
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row++, $step['expected result']);
						}
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Result');
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row++, $tc->result==0?'untested':($tc->result==1?'passed':'failed'));
						break;
						 
					default:
						 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $column);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row++, array_key_exists($column,$tc->column)?$tc->column[$column]:null);
						 
						 
						 
				}
				/*
				 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Test Method');
				 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row++, $tc->test_method);
				 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Pre-condition');
				 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row++, $tc->pre_condition);
				 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Test Steps');
				 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Actions');
				 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row++, 'Expected Result');
				 */

	    
			}//foreach
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
