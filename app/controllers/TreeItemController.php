<?php

use Illuminate\Support\Facades\Input;
class TreeItemController extends Controller
{

    public function show ($foder_id)
    {
        $doc = Document::find($foder_id);
        if ($doc->type != 'folder') {
            if($doc->latest_version() == null){
                return json_encode(array('children'=> array()));
            }
            switch ($doc->type) {
                case 'rs':
                    $items = $doc->latest_version()->rss;
                    break;
                case 'tc':
                    $items = $doc->latest_version()->tcs;
                    break;
                default:
                    $items = array();
            }
            $rt = array();
            foreach ($items as $v){
                $rt[] = array(
                        'name' => $v->tag,
                        'leaf' => 'true',
                        'id' => 'item-'.$v->id,
                        'item_id' => $v->id,
                        'type' => 'item'
                );
            }
            return json_encode(array('children'=> $rt));
        }
        
        $docs = Document::where('fid', '=', $foder_id)->get();
        $rt = array();
        foreach ($docs as $d) {
            if(Input::get('document_id')){
                //判断是否有调用关系
                $rel = Document::find(Input::get('document_id'));
                $related = false;
                foreach ($rel->dests as $v){
                    if ($v->id == $d->id){
                        $related = true;
                    }
                }
                if(!$related){
                    continue;
                }
            }
            
            
            $rt[] = array(
                    'name' => $d->name,
                    'leaf' => 'false',
                    'id' => $d->id,
                    'type' => $d->type
            );
        }
        $r = array(
                'children' => $rt
        );
        return json_encode($r);
    }
}
