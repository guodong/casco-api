<?php
use Illuminate\Support\Facades\Input;



class ExportController extends BaseController {


	public function  __construct(){
		include PATH_BASE . '/PE/Classes/PHPExcel.php';
		include PATH_BASE . '/PE/Classes/PHPExcel/Writer/Excel2007.php';
		$this->objPHPExcel = new PHPExcel();
	}


	public function __destruct() {
		unset($this->objPHPExcel);
	}

	public function childmatrix($id){

		$items=[];
		//列名与字段一一对应起来吧
		if(!$id)return [];
		$items=ChildMatrix::where('verification_id','=',$id)->orderBy('Child Requirement Tag','asc')->get();
		//列名用version的吧
		$data=[];
		foreach($items as $item){
			$da=[];
			foreach($item->toArray()  as  $k=>$v){
				$da[$k]=$v;
			}
			$item->child_type=='rs'?$child=Rs::find($item->child_id):$child=Tc::find($item->child_id);
			$item->parent_type=='rs'?$parent=Rs::find($item->parent_id):$parent=Tc::find($item->parent_id);
			$child_column=(array)json_decode('{'.$child->column.'}',true);
			array_key_exists('description',(array)$child_column)?
			$da['Child Requirement Text']=$child_column['description']:
			array_key_exists('test case description',$child_column)?$da['Child Requirement Text']=$child_column['test case description']:null;
			$column=$parent?$parent->column:null;
			foreach($column=(array)json_decode('{'.$column.'}',true) as $key=>$val){
				switch($key){
					case 'description':
						$da['Parent Requirement Text']=$column[$key];
						break;
					case 'test case description':
						$da['Parent Requirement Text']=$column[$key];
						break;
					case 'contribution':
						array_key_exists('safety',$child_column)?$da[$key]=$val.MID_COMPOSE.$child_column['safety']:$da[$key]=$val.MID_COMPOSE;
						break;
					default:
						array_key_exists($key,$child_column)?$da[$key]=$child_column[$key].MID_COMPOSE.$val
						:$da[$key]=$val.MID_COMPOSE;
				}//switch
			}//foreach
			$data[]=$da;
		}//foreach

		return  $data;


	}
	public function parentmatrix($id,$parent_v_id){

		if(!$id||!$parent_v_id)return [];
		else
		$items=ParentMatrix::where('verification_id','=',$id)->where('parent_v_id','=',$parent_v_id)->orderBy('Parent Requirement Tag','asc')->get();
		//列名用version的吧
		foreach($items as $item){
			$da=[];
			foreach($item->toArray()  as  $k=>$v){
				$da[$k]=$v;
			}
			$child=($item->child_type=='rs')?Rs::find($item->child_id):Tc::find($item->child_id);
			$parent=($item->parent_type=='rs')?Rs::find($item->parent_id):Tc::find($item->parent_id);
			$child_column=$child?(array)json_decode('{'.$child->column.'}',true):[];
			$da['justification']=[];(!$parent)?$vat_json=[]:$vat_json=json_decode($parent->vat_json);
			foreach((array)$vat_json as $val){
				array_push($da['justification'],$val->tag.':'.(property_exists($val,'comment')?$val->comment:''));
			}
			array_key_exists('description',(array)$child_column)?
			$da['Child Requirement Text']=$child_column['description']:
			(array_key_exists('test case description',$child_column)?$da['Child Requirement Text']=$child_column['test case description']:null);
			foreach($column=(array)json_decode('{'.$parent->column.'}',true) as $key=>$val){
				switch($key){
					case 'description':
						$da['Parent Requirement Text']=$column[$key];
						break;
					case 'test case description':
						$da['Parent Requirement Text']=$column[$key];
						break;
					case 'contribution':
						array_key_exists('safety',$child_column)?$da[$key]=$val.MID_COMPOSE.$child_column['safety']:$da[$key]=$val.MID_COMPOSE;
						break;
					default:
						array_key_exists($key,$child_column)?$da[$key]=$child_column[$key].MID_COMPOSE.$val
						:$da[$key]=$val.MID_COMPOSE;
				}//switch
			}//foreach
			$data[]=$da;
		}//foreach

		return  $data;
	}
	public function  filter($array,$key){

		if(is_array($array)){
			return array_key_exists($key,$array)?$array[$key]:'';
		}else if(is_object($array)){
			return property_exists($array,$key)?$array->$key:'';
		}else{
			return '';
		}

	}
	public function summary_exp($v_id,$active_sheet)
	{
		$objPHPExcel=$this->objPHPExcel;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($active_sheet);
		if ($v_id) {
			$version = (string) (Verification::find($v_id)->version);
		} else {
			$version = (string) (Verification::orderBy('created_at', 'desc')->first()->version);
		}
		$array = $this->summary($version);
		$circle = array(
            'col' => 'C',
            'row' => 4
		);
		$config = array(
            '',
            'nb of req',
            'nb req OK',
            'nb req NOK',
            'nb req NA',
            'Percent of completeness',
            '',
            '',
            '',
            'Total number of NOK items',
            'The number of NOK items( Not Complete)',
            'The number of NOK items 
	(Wrong Coverage)',
            'The number of NOK items (Logic or Description Mistake)',
            'Other'
            );

            foreach ($config as $key => $val) {
            	// $key=$key+1;
            	$objPHPExcel->setActiveSheetIndex($active_sheet)->setCellValue($circle['col'] . ($circle['row'] + $key), $val);
            	$objPHPExcel->getActiveSheet()
            	->getColumnDimension($circle['col'])
            	->setWidth(30);
            	$objPHPExcel->getActiveSheet()
            	->getStyle($circle['col'] . ($circle['row'] + $key))
            	->getBorders()
            	->getAllBorders()
            	->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            	$objPHPExcel->getActiveSheet()
            	->getStyle($circle['col'] . ($circle['row'] + $key))
            	->getFont()
            	->setName('Arial');
            	$objPHPExcel->getActiveSheet()
            	->getStyle($circle['col'] . ($circle['row'] + $key))
            	->getFont()
            	->setSize(10);
            }
            $i = 1;
            // 我查二维数组
            foreach ($array as $name => $item) {
            	$j = 0;
            	$objPHPExcel->getActiveSheet()
            	->getColumnDimension(chr(ord($circle['col']) + $i))
            	->setWidth(25);

            	foreach ($item as $key => $value) {
            		$objPHPExcel->getActiveSheet()
            		->getStyle(chr(ord($circle['col']) + $i) . ($circle['row'] + $j))
            		->getBorders()
            		->getAllBorders()
            		->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            		$objPHPExcel->getActiveSheet()
            		->getStyle(chr(ord($circle['col']) + $i) . ($circle['row'] + $j))
            		->getFont()
            		->setName('Arial');
            		$objPHPExcel->getActiveSheet()
            		->getStyle(chr(ord($circle['col']) + $i) . ($circle['row'] + $j))
            		->getFont()
            		->setSize(10);
            		$objPHPExcel->setActiveSheetIndex($active_sheet)->setCellValue(chr(ord($circle['col']) + $i) . ($circle['row'] + $j), $value);
            		if ($j == 5) {
            			$j += 3;
            		}
            		$j ++; // rows
            	} // for
            	$i ++; // cols
            } // for
            // 设置左侧version栏
            $cell = chr(ord($circle['col']) - 1) . $circle['row'];
            $range = chr(ord($circle['col']) - 1) . $circle['row'] . ':' . chr(ord($circle['col']) - 1) . ($circle['row'] + $j - 1);
            $objPHPExcel->getActiveSheet()->mergeCells($range);
            $objPHPExcel->getActiveSheet()
            ->getStyle($range)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex($active_sheet)->setCellValue($cell, $version);
            $objPHPExcel->getActiveSheet()
            ->getStyle($range)
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            return  $this->objPHPExcel;

	}

	public function export_report($project_id,$child_id,$active_sheet){

		$GLOBALS['child_id'] =$child_id;//作用域的问题罢
		$EOF="\r\n";$objPHPExcel=$this->objPHPExcel;
		if(!$child_id){
			$vefs =Verification::where('project_id','=',$project_id)->get();
		}
		$vefs =Verification::where('project_id','=',$project_id)
		->whereExists(function($query){$query->select(DB::raw(1))->from('version')->whereRaw("version.id=verification.child_version_id and version.document_id='".$GLOBALS['child_id']."'");
		})->get();
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($active_sheet);
		$num=5;
		$array_config=array('C'=>10,'D'=>15,'E'=>20,'F'=>50);
		//表头配置信息
		$column_config=array(
		array('Col'=>'C','Width'=>10,'text'=>'Version','font'=>array('Bold'=>true,'Name'=>'Arial','size'=>10),'Borders'=>PHPExcel_Style_Border::BORDER_THICK,'Fill'=>array('Type'=>PHPExcel_Style_Fill::FILL_SOLID,'Color'=>'0000CDCD')),
		array('Col'=>'D','Width'=>15,'text'=>'Author','font'=>array('Bold'=>true,'Name'=>'Arial','size'=>10),'Borders'=>PHPExcel_Style_Border::BORDER_THICK,'Fill'=>array('Type'=>PHPExcel_Style_Fill::FILL_SOLID,'Color'=>'0000CDCD')),
		array('Col'=>'E','Width'=>20,'text'=>'Date','font'=>array('Bold'=>true,'Name'=>'Arial','size'=>10),'Borders'=>PHPExcel_Style_Border::BORDER_THICK,'Fill'=>array('Type'=>PHPExcel_Style_Fill::FILL_SOLID,'Color'=>'0000CDCD')),
		array('Col'=>'F','Width'=>50,'text'=>'Description','font'=>array('Bold'=>true,'Name'=>'Arial','size'=>10),'Borders'=>PHPExcel_Style_Border::BORDER_THICK,'Fill'=>array('Type'=>PHPExcel_Style_Fill::FILL_SOLID,'Color'=>'0000CDCD'))
		);
		foreach($column_config as $val){
			$objPHPExcel->getActiveSheet()->getColumnDimension($val['Col'])->setWidth($val['Width']);
			$objPHPExcel->setActiveSheetIndex($active_sheet)->setCellValue($val['Col'].$num,$val['text']);
			$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFont()->setBold($val['font']['Bold']);
			$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFont()->setName($val['font']['Name']);
			$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFont()->setSize($val['font']['size']);
			$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getBorders()->getAllBorders()->setBorderStyle($val['Borders']);
			$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFill()->setFillType($val['Fill']['Type']);
			$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFill()->getStartColor()->setARGB($val['Fill']['Color']);
		}
		$num++;
		foreach ($vefs as $v){
			$i=1;
			if(!$v||!$v->childVersion||!$v->childVersion->document)continue;
			$description="Verify  according to".$EOF;
			$description.=$i++.')'.$v->childVersion->document->name.' '.
			$v->childVersion->name.$EOF;
			foreach($v->parentVersions as $parent){
			 $description.=$i++.')'.$parent->document->name.' '.$parent->name.$EOF;
			}
			$objPHPExcel->getActiveSheet()->getRowDimension($num)->setRowHeight(70);
			$data=array($v->version,$v->author,(string)$v->created_at,$description);
			$num1=0;
			foreach($column_config as $val){
				$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->setActiveSheetIndex($active_sheet)
				->setCellValue($val['Col'].$num,$data[$num1++]);
				$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFont()->setName($val['font']['Name']);
				$objPHPExcel->getActiveSheet()->getStyle($val['Col'].$num)->getFont()->setSize($val['font']['size']);

			}
			$num++;
		}
		return  $objPHPExcel;
	}//export_report


	public function export_childs($v_id,$active_sheet){

		$objPHPExcel=$this->objPHPExcel;
		$ver = Verification::find($v_id);
		$child_matrix =$ver->childMatrix;
		$items=$this->childmatrix($v_id);
		$column=array();
		foreach($ver->parentVersions as $key=>$value){
			//怎样来做
			$array=explode(',',$value->headers);
			foreach($array as $val){
				if($val=='description'||$val=='test case description')continue;
				!in_array($val,$column)?array_push($column,$val):null;
			}
		}
		$user = User::find(Session::get('uid'));
		$objPHPExcel->getProperties()
		->setTitle('New_Casco_Parent Matrix')
		->setSubject('PHPExcel Test Document')
		->setDescription('Test document for PHPExcel, generated using PHP classes.')
		->setKeywords('office PHPExcel php')
		->setCategory('Test result file');
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($active_sheet);
		$array_config=array('A'=>20,'B'=>20,'C'=>20,'D'=>20);$line_num=5;
		foreach($array_config as $key=>$config){
			$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($config);
		}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line_num, 'Child Requirement Tag');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line_num, 'Child Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line_num, 'Parent Requirement Tag');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line_num, 'Parent Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_num, 'Traceability');//注意头部多选
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line_num, 'No compliance description');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line_num, 'Already described in completeness');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line_num,'Verif_Assessment');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $line_num,'Verif. opinion justification');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$line_num,'CR');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$line_num,'Comment');
		$i=10;
		foreach($column as $value){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(++$i, $line_num,$value.COL_PREFIX);
			//设置自定义列宽度
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i+ord('A')))->setWidth(20);
		}
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setSize(10);
		//设置过滤
		$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line_num.':'.chr($i+ord('A')).$line_num);

		$row = 1+$line_num;
		foreach ($items as $item){
			//$item=json_decode($item,true);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $this->filter($item,'Child Requirement Tag'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $this->filter($item,'Child Requirement Text'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $this->filter($item,'Parent Requirement Tag'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $this->filter($item,'Parent Requirement Text'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $this->filter($item,'Traceability'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $this->filter($item,'No compliance description'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->filter($item,'Already described in completeness'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $this->filter($item,'Verif_Assessment'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $this->filter($item,'Verif. opinion justification'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row,$this->filter($item,'CR'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row,$this->filter($item,'Comment'));
			$j=10; $data=[];
			foreach($column as $key){

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(++$j, $row,$this->filter($item,$key));
			}
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getFont()->setSize(10);
			$row++;
		}
		return  $objPHPExcel;

	}

	public function export_parents($v_id,$parent_v_id,$active_sheet){

		$objPHPExcel=$this->objPHPExcel;
		if($v_id&&$parent_v_id){
			$items=$this->parentmatrix($v_id,$parent_v_id);
			$ver=Verification::find($v_id);
		}else{
			return [];
		}
		$value=Version::find($parent_v_id);
		$column=[];
		$array=explode(',',$value->headers);
		foreach($array as $val){
			if($val=='description'||$val=='test case description')continue;
			!in_array($val,$column)&&array_push($column,$val);
		}
		$objPHPExcel->getProperties()
		->setTitle('New_Casco_Parent Matrix')
		->setSubject('PHPExcel Test Document')
		->setDescription('Test document for PHPExcel, generated using PHP classes.')
		->setKeywords('office PHPExcel php')
		->setCategory('Test result file');
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($active_sheet);
		//添加title进去
		$circle=array('col'=>'A','row'=>3);
		$child_doc_name=$ver->childVersion->document->name;
		$parent_doc_name=$value->document->name;
		$objPHPExcel->setActiveSheetIndex($active_sheet)->setCellValue($circle['col'] . ($circle['row']), $child_doc_name.' COVERS '.$parent_doc_name);
		$objPHPExcel->getActiveSheet()
		->getColumnDimension($circle['col'])
		->setWidth(30);
		$objPHPExcel->getActiveSheet()
		->getStyle($circle['col'] . ($circle['row'] ))
		->getFont()
		->setName('Arial');
		$objPHPExcel->getActiveSheet()
		->getStyle($circle['col'] . ($circle['row']))
		->getFont()
		->setSize(10);

		$array_config=array('A'=>20,'B'=>36,'C'=>20,'D'=>20);$line_num=5;
		foreach($array_config as $key=>$config){
			$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($config);
		}
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line_num, 'Parent Requirement Tag');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line_num, 'Parent Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line_num, 'Child Requirement Tag');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line_num, 'Child Requirement Text');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line_num, 'justification');//注意头部多选
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line_num, 'Completeness');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line_num, 'No Compliance Description');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line_num,'Defect Type');
		/*$objValidation = $objPHPExcel->getActiveSheet()->getCell("D".$line_num)->getDataValidation(); //这一句为要设置数据有效性的单元格
		 $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		 -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		 -> setAllowBlank(true)
		 -> setShowInputMessage(true)
		 -> setShowErrorMessage(true)
		 -> setShowDropDown(true)
		 -> setPromptTitle('设备类型')
		 -> setFormula1('"列表项1,列表项2,列表项3"');
		 */
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $line_num,'Verif. Assesst');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $line_num,'Verif Assest justifiaction');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $line_num,'CR');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$line_num,'Comment');
		$i=11;
		foreach($column as $value){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(++$i,$line_num,$value.COL_PREFIX);
			//设置自定义列宽度
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i+ord('A')))->setWidth(20);
		}
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$line_num.':'.chr($i+ord('A')).$line_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		//设置过滤
		$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line_num.':'.chr($i+ord('A')).$line_num);
		$row = 1+$line_num;
		foreach ($items as $item){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $this->filter($item,'Parent Requirement Tag'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $this->filter($item,'Parent Requirement Text'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $this->filter($item,'Child Requirement Tag'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $this->filter($item,'Child Requirement Text'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, json_encode($this->filter($item,'justification')));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $this->filter($item,'Completeness'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $this->filter($item,'No Compliance Description'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $this->filter($item,'Defect Type'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $this->filter($item,'Verif_Assesst'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $this->filter($item,'Verif Assest justifiaction'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row,$this->filter($item,'CR'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row,$this->filter($item,'Comment'));
			$j=11; $data=[];
			foreach($column as $key){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(++$j, $row,$this->filter($item,$key));
			}
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.chr($j+ord('A')).$row)->getFont()->setSize(10);
			$row++;
		}

		return  $objPHPExcel;
	}



	public function export_all_sheet($project_id,$child_id,$v_id){

		$i=0;
		$this->export_report($project_id,$child_id,$i++);
		$this->summary_exp($v_id,$i++);
		$this->export_childs($v_id,$i++);
		$vefs =Verification::find($v_id);
		foreach($vefs->parentVersions as $parent_vers){
			$this->export_parents($v_id,$parent_vers->id,$i++);
		}


		return  $this->objPHPExcel;

	}
}//class




?>