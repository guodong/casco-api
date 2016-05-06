<?php
use Illuminate\Support\Facades\Input;

class DumpController extends Controller
{

	public function dump(){
		if(1){
			//最后一次导入视为最新版本,而不是最近一次修改!
			($item=Rs::where('tag','=',Input::get('tag'))->orderBy('created_at','desc')->first())||
			($item=Tc::where('tag','=',Input::get('tag'))->orderBy('created_at','desc')->first());
			//var_dump($item);
			if (!$item){
				return Response::json(array(
                'name' => '文档中不存在此tag!',
                'children' => array(),
                'parents' => array()
			));}else if($item->version->document->latest_version()->id!=$item->version->id){
				return Response::json(array(
                'name' => '当前tag不属于最新版本!',
                'children' => array(),
                'parents' => array()
					));
				}
				//var_dump($item);
				$document = ($item&&$item->version)?$item->version->document:null;
				$data = new stdClass();
				if ($document->type =='rs') {
					$item = Rs::find($item->id);
					$data->name = $item->tag;
					$data->children = array();
					$data->parents = array();
					foreach($item->dests() as $tc){
						$d = new stdClass();
						$d->name = $tc->tag;
						$d->isparent = true;
						$data->parents[] = $d;
					};
					foreach($item->sources() as $rs){
						$d = new stdClass();
						$d->name = $rs;
						$d->isparent = true;
						if(!in_array($d,$data->parents)){
							$d->name=$rs.':不符合';
							$d->_children=true;
							$data->parents[] = $d;
						}
					};
					foreach($item->srcs() as $rs){
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
						}//加入已经编辑的vat!
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
					/*foreach($item->sources() as $rs){
						$d = new stdClass();
						$d->name = $rs;
						$d->isparent = true;
						$data->parents[] = $d;
					};*/
					foreach($item->dests() as $tc){
						$d = new stdClass();
						$d->name = $tc->tag;
						$d->isparent = true;
						$data->parents[] = $d;
					};
					//var_dump($data->parents);
					foreach($item->sources() as $rs){
						$d = new stdClass();
						$d->name = $rs;
						$d->isparent = true;


						if(!in_array($d,$data->parents)){
							$d->name=$rs.':不符合';
							$d->_children=true;
							$data->parents[] = $d;
						}
					};
					foreach($item->srcs() as $rs){
						$d = new stdClass();
						$d->name = $tc->tag;
						$d->isparent = false;
						$data->children[] = $d;
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
