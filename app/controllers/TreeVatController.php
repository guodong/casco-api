<?php

use Illuminate\Support\Facades\Input;
class TreeVatController extends Controller
{
    
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
        $rt[] = array(
            'name' => 'Vat String',
            'leaf' => 'false',
            'id' => 'vatstr',
            'type' => 'vatstr',
        );
        $r = array('children'=>$rt);
        return json_encode($r);
    }

    public function show($foder_id)
        {
            if($foder_id == 'vatstr'){
                //vatstring
                $project = Project::find(Input::get('project_id'));
                $vatstrs = $project->vatstrs()->orderBy('name')->get();
                foreach($vatstrs as $v){
                    $rt[] = array(
                        'name' => $v->name,
                        'leaf' => 'true',
                        'id' => $v->id,
                        'type' => 'item'
                    );
                }
                $r = array('children'=>$rt);
                return json_encode($r);
            }
            
            //Vat
            $rt = array();
            $docs = Document::find($foder_id);
            if ($docs->type != 'folder') {
                $version = $docs->latest_version();
                if($version == null){
                    return json_encode(array('children'=> array()));
                }
                switch ($docs->type) {
                    case 'rs':
                        $items = Rs::where('version_id', '=', $version->id)->where('source_json', 'like', '%'.'[TSP-SyRS-0001]'.'%')->get();
                        break;
                    case 'tc':
                        $items = Tc::where('version_id', '=', $version->id)->where('source_json', 'like', '%'.$this->tag.'%')->get();
                        break;
                    default:
                        $items = array();
                }
                foreach ($items as $v){
                    $rt[] = array(
                        'name' => $v->tag,
                        'leaf' => 'true',
                        'id' => 'item-'.$v->id,
                        'item_id' => $v->id,
                        'type' => 'item'
                    );
                }
//                 echo '1';return;
                return json_encode(array('children'=> $rt));
            }

            $doc = Document::where('fid', '=', $foder_id)->get();
            foreach($doc as $d){
                $rt[] = array(
                    'name' => $d->name,
                    'leaf' => 'false',
                    'id' => $d->id,
                    'type' => $d->type
                );
            }
             return json_encode(array('children'=> $rt));
        }
    }
    
    