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

    private function getTags_down($tag,$version_id)
    {
        $rss = Rs::where("version_id","=",$version_id)->where('column', 'like', '%"source":%'.$tag.'%')->get();
        foreach ($rss as $v){
            if (!in_array($v->tag, $this->tags)){
                $this->tags[] = $v->tag;
                $this->getTags_down($v->tag,$version_id);
            }
        }
    }

    private function getTags_up($tag,$version_id)
    {
        $rs = Rs::where("version_id","=",$version_id)->where('tag','=',$tag)->first();
        if ($rs){
        	  
        	 $rs->column=json_decode('{'.$rs->column.'}');
	        if($rs->column&&property_exists($rs->column,'source')&&$array=explode(',',$rs->column->source))
	        {
	        	foreach ($array as $v){
                if (!in_array($v, $this->tags)){
                    $this->tags[] = $v;
                    $this->getTags_up($v,$version_id);
                }
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
                    //$rss = Rs::where('version_id', $version->id);
                    //$rsitem = Rs::where('version_id', $version->id)->where('tag', Input::get('tag'))->first();
                    $rsitem = Rs::find(Input::get('rs_id'));
                    if (!$rsitem){
                        $items = [];
                        break;
                    }
                    $this->getTags_down($rsitem->tag,$version->id);
                    $this->getTags_up($rsitem->tag,$version->id);
                    $items = Rs::where('version_id', $version->id)->whereIn('tag', $this->tags)->get();
                    break;
                case 'tc':
                      
                    //$items = Tc::where('version_id', $version->id)->where('column', 'like', '"source":%' . Input::get('tag') . '%');
                    //获取相关rs的tc
                    $rsitem = Rs::find(Input::get('rs_id'));
                    $this->getTags_down($rsitem->tag,$version->id);
                    $this->getTags_up($rsitem->tag,$version->id);
                    
                    $items = Tc::where('version_id','=', $version->id);
                   
                    $items->where(function ($query)use($rsitem){
                        $query->orWhere('column', 'like', '%"source":%' . $rsitem->tag . '%');
                        foreach ($this->tags as $v){
                            $query->orWhere('column', 'like', '%"source":%' . $v . '%');
                        }
                    });
                  //  var_dump($version->id);
                   // echo $items->toArray();
                   // var_dump($items->toSql());
                    $items = $items->groupBy('tag')->get();
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
    
    