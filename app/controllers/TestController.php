<?php

use Illuminate\Support\Facades\Input;
class TestController extends Controller{

    public function index()
    {
     
    	$docs=Document::where('type','rs')->get();$m=[];
    	foreach($docs as $doc){
    		$version=$doc->latest_version();
    		$tmp=$version?$version->rss:[];
    		foreach($tmp as $rss){
    		$r=DB::table('rs1')->where('tag',$rss->tag)->where('vat_json','<>','[]')->where('vat_json','<>','NULL')->where('vat_json','<>','')->
    		orderBy('updated_at','desc')->first();
    		if($r){
    			var_dump($r->tag,$r->vat_json);
    			$m[]=$r->tag;
    			//$rss->vat_json=$r->vat_json;$rss->save();
    		}
    		}
    		
    		
    		
    	}
    	var_dump(count($m));
    	
    	
    }


}
