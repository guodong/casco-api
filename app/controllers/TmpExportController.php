<?php
use Illuminate\Support\Facades\Input;


class TmpExportController extends BaseController {


	public function  __construct(){
		include PATH_BASE . '/PE/Classes/PHPExcel.php';
		include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';

		
	}

	private $tags = [];
	private function getTags_down($item)
	{
		$sss=$item->srcs();
		foreach ($sss as $v){
			if ($v&&!in_array($v->toArray(), $this->tags)){
				$tmp=$v->toArray();$tmp['mark']='down';
				$this->tags[] = $tmp;
				$this->getTags_down($v);
			}
		}
	}

	private function getTags_up($item)
	{
		if(!$item)return;
		$sss=$item->dests();
		foreach ($sss as $v){
			if ($v&&!in_array($v->toArray(), $this->tags)){
				$tmp=$v->toArray();$tmp['mark']='up';
				$this->tags[] = $tmp;
				$this->getTags_up($v);
			}
		}
	}

	public function array_column($input,$column_key,$index_key=''){

		if(!is_array($input)) return [];
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

	public function  tmp_export($filename,$parent_version_id,$child_version_id){

		$reader = PHPExcel_IOFactory::createReader('Excel2007'); //设置以Excel5格式(Excel97-2003工作簿)
		$PHPExcel = $reader->load($filename); // 载入excel文件
		$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		//$highestColumm = $sheet->getHighestColumn(); // 取得总列数
		/** 循环读取每个单元格的数据 */
		for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
			$data = $sheet->getCell('A'.$row)->getValue();$datas=null;
			$rs=Rs::where('version_id',$parent_version_id)->where('tag',strtolower($data))->first();
			if(!$rs||!$data)continue;
			//每次循环,tags都是累加进入的
			$this->tags = [];
			$this->getTags_down($rs);
			$items =Tc::where('version_id','=',$child_version_id)->whereIn('id',$this->array_column($this->tags,'id'))->get()->toArray();
			$datas=implode(',',$this->array_column($items,'tag'));
			$PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$datas);
			$PHPExcel->getActiveSheet()->getStyle('B'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			//$PHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true);
			$PHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setName('Arial');
			$PHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setSize(8);
		
		}

		return $PHPExcel;

	}








}