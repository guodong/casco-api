<?php
use Illuminate\Support\Facades\Input;

class DumpController extends Controller
{

    public function dump(){
        if(1){
        $item = Tag::where('tag', '=', Input::get('tag'))->first();
        if (! $item) {
            return Response::json(array(
                'name' => Input::get('tag'),
                'children' => array(),
                'parents' => array()
            ));
        }
        $document = $item->version->document;
        $data = new stdClass();
        
        if ($document->type == 'rs') {
            $item = Rs::find($item->id);
            $data->name = $item->tag;
            $data->children = array();
            $data->parents = array();
            foreach($item->sources() as $rs){
                $d = new stdClass();
                $d->name = $rs;
                $d->isparent = true;
                $data->parents[] = $d;
            };
            foreach($item->tcs() as $tc){
                $d = new stdClass();
                $d->name = $tc->tag;
                $d->isparent = false;
                $data->children[] = $d;
            };
            foreach($item->rss() as $rs){
                $d = new stdClass();
                $d->name = $rs->tag;
                $d->isparent = false;
                $data->children[] = $d;
            };
            $item->vat = json_decode($item->vat_json)?json_decode($item->vat_json):[];
            foreach ($item->vat as $v){
                $has = false;
                foreach ($data->children as $c){
                    if ($c->name == $v->tag){
                        $has = true;
                        break;
                    }
                }
                if($has){
                    continue;
                }
                $d = new stdClass();
                $d->name = $v->tag;
                $d->isparent = false;
                $data->children[] = $d;
            };
            
        } else {
            $item = Tc::find($item->id);
            $data->name = $item->tag;
            $data->children = array();
            $data->parents = array();
            foreach($item->sources() as $rs){
                $d = new stdClass();
                $d->name = $rs;
                $d->isparent = true;
                $data->parents[] = $d;
            };
        }
        return Response::json($data);
        
        $document = Document::find(Input::get('document_id'));
        $data = new stdClass();
        $data->name = $document->name;
        $data->children = array();
        if ($document->type == 'rs') {
            $document->rss->each(function ($rs) use($data) {
                $d = new stdClass();
                $d->name = $rs->tag;
                $d->children = array();
                $d->parents = [];
                $rs->tcs->each(function ($tc) use($d) {
                    $d->children[] = array(
                        'name' => $tc->tag
                    );
                    $d->parents[] = array(
                        'name' => $tc->tag
                    );
                });
                $data->children[] = $d;
            });
        } elseif ($document->type == 'tc') {
            $document->tcs->each(function ($tc) use($data) {
                $d = new stdClass();
                $d->name = $tc->tag;
                $d->children = array();
                $tc->sources->each(function ($rs) use($d) {
                    $d->children[] = array(
                        'name' => $rs->tag
                    );
                });
                $data->children[] = $d;
            });
        }
        $d = array(
            'name' => 'root',
            'children' => array(
                array(
                    'name' => '123',
                    'id' => 1
                ),
                array(
                    'name' => '234',
                    'children' => array(
                        array(
                            'name' => 'c1'
                        ),
                        array(
                            'id' => 1,
                            'name' => 'c2'
                        )
                    )
                )
            )
        );
        
        // return $d;
    }
        return '{"name":"[TSP-SyRS-0001]","children":[{"name":"[TSP-SyRTC-011]","isparent": true},{"name":"2","isparent":true,"parents":[{"name":1,"isparent":true}]}],"parents":[{"name":"[TSP-SyRTC-01117]","isparent":false},{"name":"21","isparent":false}]}';
        return Response::json($data);
    }

    public function index()
    {
        $rss = Rs::where('document_id', '=', $_GET['document_id'])->get();
        foreach ($rss as $v) {
            $v->result = 1;
            if (count($v->tcs) == 0 && $v->vatstr_result == 0) {
                $v->result = 0;
                continue;
            }
            foreach ($v->tcs as $vv) {
                if ($vv->result == 2) {
                    $v->result = 2;
                    break;
                } elseif ($vv->result == 0) {
                    $v->result = 0;
                }
            }
            if ($v->result == 1) {
                if ($v->vatstr_result == 2) {
                    $v->result = 2;
                }
            }
            $v->vat;
            $v->vatstr;
        }
        return $rss;
    }

    public function update($id)
    {
        $m = Rs::find($id);
        $m->vatstr_id = Input::get('vatstr_id');
        $m->save();
        $m->vat()->detach();
        foreach (Input::get('vat') as $v) {
            $m->vat()->attach($v['id']);
        }
    }
}
