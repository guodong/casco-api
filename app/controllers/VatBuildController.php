<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class VatBuildController extends VatExportController{

	public function index(){
		$ans = [];
		$vats = Project::find(Input::get('project_id'))->vatbuilds;
		if(Input::get('document_id')){
			foreach($vats as $v){
			    if(!$v) continue;
			    $docVersions = $v->docVersions ? $v->docVersions : []; 
			    foreach($docVersions as $vv){
				    if($vv->document->id == Input::get('document_id')){ //ORM的会返回
				        $v->tc_version_id = $vv->id;
				    }
			     }
			     if($v->tc_version_id) $ans[] = $v;
			}
		}elseif(Input::get('type')){
		    foreach($vats as $v){
		        if(!$v) continue;
		        $tc_versions = $v->tcVersions();
		        foreach($tc_versions as $vv){
		            $vv->document; //object
// 		            var_dump($vv->document); 
		        }
		        $rs_versions = $v->rsVersions();
		        foreach($rs_versions as $vv){
		            $vv->document;
		        }
// 		        $data = new stdClass();
// 		        $data->vat_build = $v;
// 		        $data->tc_versions = $tc_versions;
// 		        $data->rs_versions = $rs_versions;
                $v_arr = json_decode(json_encode($v),true);
                $v_arr['tc_versions'] = $tc_versions;
                $v_arr['rs_versions'] = $rs_versions;
		        $ans[]=(object)$v_arr;
		    }
		}else{
		    foreach ($vats as $v){
		        if(!$v) continue;
		        $docVersions = $v->docVersions ? $v->docVersions : [];
		        foreach($docVersions as $vv){
		            $vv->document;
		        }
		        $ans[] = $v;
		    }
		}
		return $ans;
	}

	public function store(){
		$vats = VatBuild::create(Input::get());
		foreach (Input::get('doc_versions') as $v){
			if(array_key_exists('doc_version_id', $v)){
				$vats->docVersions()->attach($v['doc_version_id']);
			}
		}
		return $vats;
	}

	public function update($id)
	{
		$vat = VatBuild::find($id);
        $data=Input::get();
	    $vat->update($data);
	    
	    return $vat;
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
		$vat_tc=[];$parent_vat=[];$vat_parent=[];
		$parent_versions=[];
		$vatbuild = VatBuild::find(Input::get('vat_build_id'));
		$tcversion = Version::find(Input::get('tc_version_id'));
		$tcdoc = $tcversion->document;
		$tc_tags = $tcversion->tcs;
// 		$docversions = $vatbuild->docVersions;
// 		$rsversions = [];
// 		foreach ($docversions as $dv){
// 		    $doci = Document::find($dv->document_id);
// 		    if($doci->type == 'rs') $rsversions[]=$dv;
// 		}
        $rsversions = $vatbuild->rsVersions();
		$parent_docs=$tcdoc->dest(); //直接父文档信息 如果有重复的情况，就用dest()吧
		foreach($rsversions as $rsversion){ 
		    foreach($parent_docs as $pd){
		        if($pd->id==$rsversion->document_id) 
		            array_push($parent_versions,$rsversion);
		    }
		}
		
		$relation_json['vat_build_id']=$vatbuild->id;
		$relation_json['vat_build_name']=$vatbuild->name;
		$relation_json['tc_doc_name']=$tcdoc->name;
		$relation_json['tc_version_id']=$tcversion->id;
		$relation_json['tc_version_name']=$tcversion->name;
		
		//封装所有RS的VAT含有该TC item的条目
		foreach ($rsversions as $rsversion){ //取rs vat_json中type=tc的id在tc_tag中检索
			$vat_tc_each=[];$vat_rs_each=[]; $parent_vat_each=[];
			$rs_tags = $rsversion->rss;
			$rsdoc = $rsversion->document;
			
			//判断当前遍历的是否为其直接父文档
			$found_flag=0;
			foreach($parent_versions as $parent_version){
			    if($rsversion==$parent_version) {
			        $found_flag=1;break;
			    }
			}
			
			foreach($rs_tags as $rs_tag){
			    $each_vat=[];
				$tc_each_vat=[];$rs_each_vat=[];
				$pt_each_vat=[];$pt_each_comment=[];
				$rs_vat_json = $rs_tag->vat_json;
				
				//封装直接父文档的VAT信息
				if($found_flag){
				    $parent_vat_objs=json_decode($rs_vat_json);
				    foreach ($parent_vat_objs as $parent_vat_obj){
				        array_push($pt_each_vat,$parent_vat_obj->tag);
				        if(property_exists($parent_vat_obj, 'comment') && $parent_vat_obj->comment){
				            $vat_comment='{'.$parent_vat_obj->tag.":".$parent_vat_obj->comment.'}';
				            array_push($pt_each_comment,$vat_comment);
				        }
				    }
				}
				
				if($rs_vat_json && $rs_vat_json != 'Array' && $rs_vat_json != '[]'){
					$rs_vat_json_objs = json_decode($rs_vat_json); //对象数组
					foreach($rs_vat_json_objs as $rs_vat_json_obj){ //对象
						//vat_json中包含该tc item
						if($rs_vat_json_obj->type == 'tc'){
							$tc_tag = DB::table('tc')->where('version_id',$tcversion->id)->where('id',$rs_vat_json_obj->id)->first();
							if($tc_tag){
							    $exist_flag=0;
							    foreach ($each_vat as &$each_vat_i){
							        if($each_vat_i['vat_version_id']==$tcversion->id){
							            $str_tmp=$each_vat_i['vat_tag_name'].",".$tc_tag->tag;
							            $each_vat_i['vat_tag_name']=$str_tmp;
							            if(property_exists($rs_vat_json_obj, 'comment') && $rs_vat_json_obj->comment){
							                $each_vat_i['vat_comment'] .= '{'.$rs_vat_json_obj->tag.":".$rs_vat_json_obj->comment.'}';
							            }
							            $exist_flag=1;
							            break;
							        }
							    }
							    if(!$exist_flag){
							        $tmp=array(
							            "rs_version_id"=>$rsversion->id,
							            "rs_version_name"=>$rsversion->name,
							            "rs_tag_name"=>$rs_tag->tag,
							            "rs_doc_name"=>$rsdoc->name,
							            "vat_version_id"=>$tcversion->id,
							            "vat_version_name"=>$tcversion->name,
							            "vat_doc_name"=>$tcdoc->name,
							            "vat_tag_name"=>$tc_tag->tag,
							        );
							        if(property_exists($rs_vat_json_obj, 'comment') && $rs_vat_json_obj->comment){
							            $tmp['vat_comment']='{'.$rs_vat_json_obj->tag.":".$rs_vat_json_obj->comment.'}';
							        }else{
							            $tmp['vat_comment']='';
							        }
							        array_push($each_vat,$tmp);
							    }
							} //tc
						}else if($rs_vat_json_obj->type == 'rs'){ //vat_json包含其直接父文档item
						    foreach($parent_versions as $parent_version){
						        $rs_item = DB::table('rs')->where('version_id',$parent_version->id)->where('id',$rs_vat_json_obj->id)->first();
						        if($rs_item) {
						            $parent_doc=DB::table('document')->where('id',$parent_version->document_id)->first();
						            $exist_flag=0;
						            foreach ($each_vat as &$each_vat_i){
						                if($each_vat_i['vat_version_id']==$parent_version->id){
						                    $str_tmp=$each_vat_i['vat_tag_name'].",".$rs_item->tag;
						                    $each_vat_i['vat_tag_name']=$str_tmp;
						                    if(property_exists($rs_vat_json_obj, 'comment') && $rs_vat_json_obj->comment){
						                        $each_vat_i['vat_comment'] .= '{'.$rs_vat_json_obj->tag.":".$rs_vat_json_obj->comment.'}';
						                    }
						                    $exist_flag=1;
						                    break;
						                }
						            }
						            if(!$exist_flag){
						                $tmp=array(
						                    "rs_version_id"=>$rsversion->id,
						                    "rs_version_name"=>$rsversion->name,
						                    "rs_tag_name"=>$rs_tag->tag,
						                    "rs_doc_name"=>$rsdoc->name,
						                    "vat_version_id"=>$parent_version->id,
						                    "vat_version_name"=>$parent_version->name,
						                    "vat_doc_name"=>$parent_doc->name,
						                    "vat_tag_name"=>$rs_item->tag,
						                );
						                if(property_exists($rs_vat_json_obj, 'comment') && $rs_vat_json_obj->comment){
						                    $tmp['vat_comment']='{'.$rs_vat_json_obj->tag.":".$rs_vat_json_obj->comment.'}';
						                }else{
						                    $tmp['vat_comment']='';
						                }
						                array_push($each_vat,$tmp);
						            }
						        }
						    }
						}//type==rs foreach
					}
				} //rs_vat_json_objs foreach
				
				//其他阶段分配给本阶段的，包括vat_json中包含该TC的item以及包含其直接父文档tag的items
				if($each_vat){
				    foreach ($each_vat as &$each_vat_i){ //非引用的问题？
				        $vat_tc_each[]=$each_vat_i;
				    }
				}
				
				//Parent item Vat组装
				if($found_flag){
				    $tmp_parent=array(
				        "rs_version_id"=>$rsversion->id,
				        "rs_version_name"=>$rsversion->name,
				        "rs_tag_name"=>$rs_tag->tag,
				        "rs_doc_name"=>$rsdoc->name,
				    );
				    $tmp_parent['rs_vat']=implode(',', $pt_each_vat);
				    $tmp_parent['rs_vat_comment']=implode(',', $pt_each_comment);
				    array_push($parent_vat_each, $tmp_parent);
				}
			}//rs_tags foreach
			
            if($found_flag) $parent_vat[]=$parent_vat_each;
			if($vat_tc_each) $vat_tc[]=$vat_tc_each;
		}//rsversions foreach
		
        $relation_json['parent_vat']=$parent_vat;
		$relation_json['vat_tc']=$vat_tc;
		return $relation_json;
	}
	
	public function assigned_export(){
	    if(!Input::get('vat_build_id') || !$vats=$this->show()) return [];
	    $vat_tc=$vats['vat_tc'];
	    $vat_tc_ex=[];
	    if(Input::get('rs_version_id')){
	        $active_sheet=0;
	        foreach($vat_tc as $vat_tc_i){
	            if(Input::get('rs_version_id')==$vat_tc_i[0]['rs_version_id']){
	                $vat_tc_ex=$vat_tc_i;
	                break;
	            }
	        }
	        $objPHPExcel=parent::get_assigned($vat_tc_ex,$active_sheet);
	    }else{
	        $active_sheet=0;
	        foreach($vat_tc as $vat_tc_i){
	            $objPHPExcel=parent::get_assigned($vat_tc_i,$active_sheet);
	            $active_sheet++;
	        }
	    }
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="其他阶段分配给本阶段的需求.xls"');
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
	
	public function assign_export(){
	    if(!Input::get('vat_build_id') || !$vats=$this->show()) return [];
	    $parent_vat=$vats['parent_vat'];
	    $parent_vat_ex=[];
	    if(Input::get('rs_version_id')){
	        $active_sheet=0;
	        foreach($parent_vat as $parent_vat_i){
	            if(Input::get('rs_version_id')==$parent_vat_i[0]['rs_version_id']){
	                $parent_vat_ex=$parent_vat_i;
	                break;
	            }
	        }
	        $objPHPExcel=parent::get_assign($parent_vat_ex,$active_sheet);
	    }else{
	        $active_sheet=0;
	        foreach($parent_vat as $parent_vat_i){
	            $objPHPExcel=parent::get_assign($parent_vat_i,$active_sheet);
	            $active_sheet++;
	        }
	    }
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="本阶段分配给其他阶段的需求.xls"');
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
	
	public function all_export(){
	    $type=Input::get('type');
	    switch ($type){
	        case 'Assign':
	            $this->assign_export();
	            break;
	        case 'Assigned':
	            $this->assigned_export();
	            break;
	       default: break;
	    }
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
	
}

