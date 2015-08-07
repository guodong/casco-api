<?php

class TreeController extends Controller{

    public function index()
    {
        return Document::whereRaw("fid = 0")->get();
        if(isset($_GET['node'])){
            $docs = Document::where('fid', '=', $_GET['node'])->get();
        }else{
            $docs = Document::whereRaw("project_id = {$_GET['project_id']} and fid = 0")->get();
        }
        $rt = array();
        foreach($docs as $d){
            $lf = ($d->type == 'folder')?false:true;
            $rt[] = array(
                    'name' => $d->name,
                    'leaf' => $lf,
                    'id' => $d->id,
                    'type' => $d->type,
                    'versions' => $d->versions
            );
        }
        $r = array('children'=>$rt);
        return json_encode($r);
    }

    public function root()
    {
         $docs = Document::whereRaw("project_id = ? and fid = ?", array($_GET['project_id'], 0))->get();
         $rt = array();
         foreach($docs as $d){
             $lf = ($d->type == 'folder')?false:true;
             $rt[] = array(
                     'name' => $d->name,
                     'leaf' => $lf,
                     'id' => $d->id,
                     'type' => $d->type,
                    'versions' => $d->versions
             );
         }
         $r = array('children'=>$rt);
         return json_encode($r);
    }
    
    public function show($foder_id)
    {
        $docs = Document::where('fid', '=', $foder_id)->get();
        $rt = array();
        foreach($docs as $d){
            $lf = ($d->type == 'folder')?false:true;
            $rt[] = array(
                    'name' => $d->name,
                    'leaf' => $lf,
                    'id' => $d->id,
                    'type' => $d->type,
                'versions' => $d->versions
            );
        }
        $r = array('children'=>$rt);
        return json_encode($r);
    }
    
    public function treemod()
    {
        $src = Document::find($_GET['src']);
        $dst = Document::find($_GET['dst']);
        if($dst->type == 'folder'){
            $src->fid = $dst->id;
            $src->save();
        }else{
            $src->fid = $dst->fid;
            $src->save();
        }
    }
}
