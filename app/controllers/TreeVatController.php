<?php
use Illuminate\Support\Facades\Input;

class TreeVatController extends Controller
{

    public function root()
    {
        $docs = Document::whereRaw("project_id = ? and fid = ?", array(
            $_GET['project_id'],
            0
        ))->get();
        $rt = array();
        foreach ($docs as $d) {
            $rt[] = array(
                'name' => $d->name,
                'leaf' => false,
                'id' => $d->id,
                'type' => $d->type,
                'versions' => $d->versions
            );
        }
        $rt[] = array(
            'name' => 'Vat String',
            'leaf' => 'false',
            'id' => 'vatstr',
            'type' => 'vatstr'
        );
        $r = array(
            'children' => $rt
        );
        return json_encode($r);
    }
    
    private $tags = [];

    private function getTags($tag)
    {
        $rss = Rs::where('source_json', 'like', '%'.$tag.'%')->get();
        foreach ($rss as $v){
            if (!in_array($v->tag, $this->tags)){
                $this->tags[] = $v->tag;
                $this->getTags($v->tag);
            }
        }
    }

    private function getTags1($tag)
    {
        $rs = Rs::where('tag', $tag)->first();
        if ($rs){
            foreach (json_decode($rs->source_json) as $v){
                if (!in_array($v, $this->tags)){
                    $this->tags[] = $v;
                    $this->getTags1($v);
                }
            }
        }
    }

    public function show($foder_id)
    {
        if ($foder_id == 'vatstr') {
            // vatstring
            $project = Project::find(Input::get('project_id'));
            $vatstrs = $project->vatstrs()
                ->orderBy('name')
                ->get();
            $rt = [];
            foreach ($vatstrs as $v) {
                $rt[] = array(
                    'name' => $v->name,
                    'leaf' => 'true',
                    'id' => $v->id,
                    'type' => 'vat'
                );
            }
            $r = array(
                'children' => $rt
            );
            return json_encode($r);
        }
        
        // Vat
        $rt = array();
        $docs = Document::find($foder_id);
        if ($docs->type != 'folder') {
            $version = $docs->latest_version();
            if ($version == null) {
                return json_encode(array(
                    'children' => array()
                ));
            }
            switch ($docs->type) {
                case 'rs':
                    $rss = Rs::where('version_id', $version->id);
                    $rsitem = Rs::where('tag', Input::get('tag'))->first();
                    $this->getTags($rsitem->tag);
                    $this->getTags1($rsitem->tag);
                    $items = Rs::where('version_id', $version->id)->whereIn('tag', $this->tags)->get();
                    break;
                case 'tc':
                    $items = Tc::where('version_id', $version->id)->where('source_json', 'like', '%' . Input::get('tag') . '%')->get();
                    break;
                default:
                    $items = array();
            }
            foreach ($items as $v) {
                $rt[] = array(
                    'name' => $v->tag,
                    'leaf' => true,
                    'id' => 'item-' . $v->id,
                    'item_id' => $v->id,
                    'type' => $docs->type
                );
            }
            // echo '1';return;
            return json_encode(array(
                'children' => $rt
            ));
        }
        
        $doc = Document::where('fid', '=', $foder_id)->get();
        foreach ($doc as $d) {
            $rt[] = array(
                'name' => $d->name,
                'leaf' => 'false',
                'id' => $d->id,
                'type' => $d->type
            );
        }
        return json_encode(array(
            'children' => $rt
        ));
    }
}
    
    