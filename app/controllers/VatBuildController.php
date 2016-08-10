<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class VatBuildController extends TmpExportController{


	public function index(){
		$ans = [];
		$vats = Project::find(Input::get('project_id'))->vatbuilds;
		if(!Input::get('document_id')){
			foreach($vats as $v)
			{$v->tcVersion && $v->tcVersion->document;
			foreach($v->rsVersions as $rsv){
				$rsv->document;
			}
			}
			return  $vats;
		}
		foreach ($vats as $v){
			if(!$v) continue;
			if($v->tcVersion && $v->tcVersion->document->id == Input::get('document_id')){
				$v->tcVersion->document;
				$rsVersions = $v->rsVersions ? $v->rsVersions : [];
				foreach($rsVersions as $vv){
					$vv->document;
				}
				$ans[] = $v;
			}
		}
		return $ans;
	}

	public function store(){
		if(!Input::get('tc_version_id') || !Input::get('name')) return;
		$vats = VatBuild::create(Input::get());
		foreach (Input::get('rs_versions') as $v){
			if(array_key_exists('rs_version_id', $v)){
				$vats->rsVersions()->attach($v['rs_version_id']);
			}
		}
		return $vats;
	}

	public function export(){
			
		if((!$p_vid=Input::get('parent_version_name'))||(!$c_vid=Input::get('child_version_name')))return [];
		$name = uniqid () . '.doc';  $filename=public_path () . '/files/' . $name;
		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $filename);
		$objPHPExcel=parent::tmp_export($filename,$p_vid,$c_vid);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="result.xls"');
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
	public function destroy($id){
		$vats = VatBuild::find($id);
		foreach ($vats->vatRss as $v){
			$v->delete();
		}
		$vats->destroy($id);
		return $vats;
	}

	public function show(){
		$relation_json = [];
		$tc_vat=[];
		$vat_tc=[];
		$tc_exist=[];$index=0;
		$vatbuild = VatBuild::find(Input::get('vat_build_id'));
		$tcversion = $vatbuild->tcVersion;
		$tcdoc = $tcversion->document;
		$tc_tags = $tcversion->tcs;
		//        var_dump($tc_tags);
		$rsversions = $vatbuild->rsVersions;
		$relation_json['vat_build_id']=$vatbuild->id;
		$relation_json['vat_build_name']=$vatbuild->name;
		$relation_json['tc_doc_name']=$tcdoc->name;
		$relation_json['tc_version_id']=$tcversion->id;
		$relation_json['tc_version_name']=$tcversion->name;
		foreach ($rsversions as $rsversion){ //取rs vat_json中type=tc的id在tc_tag中检索
			$vat_tc_each=[];
			$rs_tags = $rsversion->rss;
			$rsdoc = $rsversion->document;
			foreach($rs_tags as $rs_tag){
				$tcs=[];
				$rs_vat_json = $rs_tag->vat_json;
				if($rs_vat_json && $rs_vat_json != 'Array' && $rs_vat_json != '[]'){
					$rs_vat_json_objs = json_decode($rs_vat_json); //对象数组
					//                    var_dump($rs_vat_json_objs);
					foreach($rs_vat_json_objs as $rs_vat_json_obj){ //对象
						//                        var_dump($rs_vat_json_obj);
						if($rs_vat_json_obj->type == 'tc'){
							$tc_tag = DB::table('tc')->where('version_id',$vatbuild->tc_version_id)->where('id',$rs_vat_json_obj->id)->first();
							if($tc_tag){
								if(!array_key_exists($tc_tag->id, $tc_exist)){ //Index
									$tc_exist[$tc_tag->id]=$index;
									//                                    var_dump($tc_exist);
									$tmp_tc = array(
                                       "tc_tag_name"=>$tc_tag->tag,
                                       "tc_version_id"=>$tcversion->id,
                                       "tc_version_name"=>$tcversion->name,
                                       "rs_version_name"=>$rsversion->name,
                                       "rs_tag_name"=>$rs_tag->tag,
                                       "rs_doc_name"=>$rsdoc->name,
									);
									$tc_vat[$index]=$tmp_tc;
									$index++;
								}else{
									$i=$tc_exist[$tc_tag->id];
									//                                    array_push($tc_vat[$i]['rs_tag_name'], $rs_tag->name);
									//                                    var_dump($tc_vat[$i]['rs_tag_name']);
									$str=$tc_vat[$i]['rs_tag_name'] .",".$rs_tag->tag;
									$tc_vat[$i]['rs_tag_name']=$str;
								}
								array_push($tcs,$tc_tag->tag);
							}
						}
					}
				}
				if($tcs){
					$tmp_vat=array(
                       "tc_version_name"=>$tcversion->name,
                       "rs_version_id"=>$rsversion->id,
                       "rs_version_name"=>$rsversion->name,
                       "rs_tag_name"=>$rs_tag->tag,
                       "rs_doc_name"=>$rsdoc->name,
					);
					$tmp_vat['tc_tag_name']=implode(',',$tcs);
					$vat_tc_each[]=$tmp_vat;
				}
			}
			if($vat_tc_each){
				$vat_tc[]=$vat_tc_each;
			}
		}
		$relation_json['tc_vat']=$tc_vat;
		$relation_json['vat_tc']=$vat_tc;
		return $relation_json;
	}

}

