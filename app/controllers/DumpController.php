<?php
use Illuminate\Support\Facades\Input;

class DumpController extends Controller
{
	
	public function dump(){
	
		if($id=Input::get('id')){
			 ($item=Rs::find($id))||($item=Tc::find($id));
		}else {
		 	 return [];
		}
			if (!$item){
				return Response::json(array(
                'name' => '文档中不存在此tag!',
                'children' => array(),
                'parents' => array()
			));
			}else if($item->version->document->latest_version()->id!=$item->version->id){
				
				//可能再也不用纠结这个问题了
				return Response::json(array(
                'name' => '当前tag不属于最新版本!',
                'children' => array(),
                'parents' => array()
					));
				}
				$document = ($item&&$item->version)?$item->version->document:null;
				$data = new stdClass();
				if ($document->type =='rs') {
					$item = Rs::find($item->id);
					$data->id=$item->id;
					$data->name = $item->tag;
					$data->children = array();
					$data->parents = array();
					foreach($item->dests() as $tc){
						$d = new stdClass();
						$d->name = $tc->tag;
						$d->id=$tc->id;
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
						$d->id=$rs->id;
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
						$d->id=$v->id;
						$d->isparent = false;
						$data->children[] = $d;
					};

				} else {
					$item = Tc::find($item->id);
					$data->name = $item->tag;
					$data->id   =$item->id;
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
						$d->id=$tc->id;
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
						$d->id=$tc->id;
						$d->isparent = false;
						$data->children[] = $d;
					};
				}
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
	
 	public function dump_tag(){
 	
 	 if(!$id=Input::get('id')) return [];
 	 $item=(Tc::find($id))||(Rs::find($id));
 	 $item->version;
 	 $item->document;
 	 $item->project;
 	 return  $item;
 	
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
