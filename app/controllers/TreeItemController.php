<?php

class TreeItemController extends Controller
{

    public function show ($foder_id)
    {
        $doc = Document::find($foder_id);
        if ($doc->type != 'folder') {
            switch ($doc->type) {
                case 'rs':
                    $items = $doc->rss;
                    break;
                case 'tc':
                    $items = $doc->tcs;
                    break;
                default:
                    $items = array();
            }
            $rt = array();
            foreach ($items as $v){
                $rt[] = array(
                        'name' => $v->title,
                        'leaf' => 'true',
                        'id' => 'item-'.$v->id,
                        'type' => 'item'
                );
            }
            return json_encode(array('children'=> $rt));
        }
        $docs = Document::where('fid', '=', $foder_id)->get();
        $rt = array();
        foreach ($docs as $d) {
            //$lf = ($d->type == 'folder') ? false : true;
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
