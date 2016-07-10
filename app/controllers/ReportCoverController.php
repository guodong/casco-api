<?php

use Illuminate\Support\Facades\Input;

class ReportCoverController extends ExportController {

	public function index(){
		$items=[];
		//列名与字段一一对应起来吧
		if($id=Input::get('report_id')){
			$items=ReportCover::where('report_id','=',$id)->orderBy('Parent Requirement Tag','asc')->get();
		}else{
			return [];
		}

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
			(array_key_exists('test case description',$child_column)?$da['Child Requirement Text']=$child_column['test case description']:null);
			$column=$parent?$parent->column:null;
			foreach($column=(array)json_decode('{'.$column.'}',true) as $key=>$val){
				switch($key){
					case 'description':
						$da['Parent Requirement Text']=$column[$key];
						break;
					case 'test case description':
						$da['Parent Requirement Text']=$column[$key];
						break;
						/*case 'contribution':
						 array_key_exists('safety',$child_column)?$da[$key]=$val.MID_COMPOSE.$child_column['safety']:$da[$key]=$val.MID_COMPOSE;
						 break;
						 */
					default:
						//array_key_exists($key,$child_column)?$da[$key]=$val.MID_COMPOSE.$child_column[$key]
						//:$da[$key]=$val.MID_COMPOSE;
				}//switch
			}//foreach
			$data[]=$da;
		}//foreach

		return  $data;
	}

	


}



?>