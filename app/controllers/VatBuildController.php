<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class VatBuildController extends BaseController{
    
   public function index(){
       $ans = [];
       $vats = Project::find(Input::get('project_id'))->vatbuilds;
//        if(!Input::get('document_id')){
//        	foreach($vats as $v)
//        	{$v->tcVersion;$v->rsVersions;}
//        	return  $vats;
//        }
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
            $vats->rsVersions()->attach($v['rs_version_id']);
        }
        
        return $vats;
   }
   
   public function destroy($id){
       $vats = VatBuild::find($id);
//        var_dump($vats->vatRss);exit;
       foreach ($vats->vatRss as $v){
           $v->delete();
       }
       $vats->destroy($id);
       return $vats;
   }
   
   public function show(){
       $json = [];
       $vatbuild = VatBuild::find(Input::get('vat_build_id'));
       $tcversion = $vatbuild->tcVersion;
       $tcdoc = $tcversion->document;
       $tc_tags = $tcversion->tcs;
       $rsversions = $vatbuild->rsVersions; 
       foreach ($rsversions as $rsversion){ //取rs vat_json中type=tc的id在tc_tag中检索
           $rs_tags = $rsversion->rss;
           $rsdoc = $rsversion->document;
           foreach($rs_tags as $rs_tag){
               $rs_vat_json = $rs_tag->vat_json;
               if($rs_vat_json && $rs_vat_json != 'Array'){ //NULL ? 到底为空时是个什么情况
                   $rs_vat_json_objs = json_decode($rs_vat_json,true);
                   foreach($rs_vat_json as $rs_vat_json_obj){
                       if($rs_vat_json_obj->type == 'tc'){
                           if($tc_tags->has($rs_vat_json_obj->id)){
                               $tc_tag = $tc_tags->get($rs_vat_json_obj->id);
                               $tmp = array("tc_version_id"=>$tcversion->id,"tc_version_name"=>$tcversion->name,
                                   "tc_tag_id"=>$tc_tag->id,"tc_tag_name"=>$tc_tag->tag,
                                   "tc_doc_id"=>$tcdoc->id,"tc_doc_name"=>$tcdoc->name,
                                   "rs_version_id"=>$rsversion->id,"tc_version_name"=>$rsversion->name,
                                   "rs_tag_id"=>$rs_tag->id,"tc_tag_name"=>$rs_tag->tag,
                                   "rs_doc_id"=>$rsdoc->id,"tc_doc_name"=>$rsdoc->name
                               );
                               var_dump($tmp);
                           }
                               
                                
                       }
                   }
//                    var_dump($rs_vat_json_objs);
               }
//                var_dump($rs_vat_json);
           }
//            $rs_tags[] = $rs_tag;
//            var_dump($rs_tag);
       }
//        var_dump($rs_tags);
       
   }
    
}
    
    