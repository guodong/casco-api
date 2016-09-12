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
	private $srcs=[];
	private $dests=[];
	public function getTags_down($item)
	{
		if(!$item)return;//if(!$item||!$item->verison||!$item->version->document){echo 'error';var_dump($item);echo 'shit';var_dump($item->version);return;}
		$sss=$item->srcs();
		foreach ($sss as $v){
			if ($v&&!in_array($v->toArray(), $this->tags)){
				$tmp=$v->toArray();
				$this->tags[] = $tmp;
				$this->getTags_down($v);
			}
		}
	}

	public  function getTags_up($item)
	{
		if(!$item)return;
		$sss=$item->dests();
		//var_dump($sss);
		foreach ($sss as $v){
			if ($v&&!in_array($v->toArray(), $this->tags)){
				$tmp=$v->toArray();
				$this->tags[] = $tmp;
				$this->getTags_up($v);
			}
		}
	}


	public function array_column($input,$column_key,$index_key=''){

		if(!is_array($input)) return [];
		$results=array();
		if($column_key===null){
			if(!is_string($index_key)&&!is_int($index_key)) return false;
			foreach($input as $_v){
				if(array_key_exists($index_key,$_v)){
					$results[$_v[$index_key]]=$_v;
				}
			}
			if(empty($results)) $results=$input;
		}else if(!is_string($column_key)&&!is_int($column_key)){
			return false;
		}else{
			if(!is_string($index_key)&&!is_int($index_key)) return false;
			if($index_key===''){
				foreach($input as $_v){
					if(is_array($_v)&&array_key_exists($column_key,$_v)){
						$results[]=$_v[$column_key];
					}
				}
			}else{
				foreach($input as $_v){
					if(is_array($_v)&&array_key_exists($column_key,$_v)&&array_key_exists($index_key,$_v)){
						$results[$_v[$index_key]]=$_v[$column_key];
					}
				}
			}

		}
		return $results;
	}


	function doc_down($sr,$mark){
		if(!$sr)  return [];
		($mark=='src')?$this->srcs[]=$sr->toArray():$this->dests[]=$sr->toArray();
		foreach($sr->src() as $item){
			$this->doc_down($item,$mark);
		}
	}

	function doc_up($sr,$mark){
		if(!$sr)  return [];
		($mark=='src')?$this->srcs[]=$sr->toArray():$this->dests[]=$sr->toArray();
		foreach($sr->dest() as $item){
			$this->doc_up($item,$mark);
		}
	}

	function current_doc($items){
		$arr=[];
		while(!$item=array_shift($items)){
			if($item&&$item->version->document->id==$this->foder_id){
				$arr[]=$item;
			}else{continue;}
		}
		return  $arr;
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
			$rsitem = Rs::find(Input::get('rs_id'));
			$this->doc_up($docs,'dest');
			$this->doc_down($docs,'dest');
			$this->doc_up($rsitem->version->document,'src');
			$this->doc_down($rsitem->version->document,'src');
			$this->getTags_down($rsitem);
			$this->getTags_up($rsitem);
			//var_dump($this->srcs);
			$merge=[];
			foreach($this->srcs as $t){
				if(in_array($t,$this->dests))$merge[]=$t;
			}
			//var_dump($merge);
			if(count($merge)==0){
				return json_encode(array(
                    'children' => array()
				));
			}else if(count($merge)>1){

			}else if(count($merge)==1){//说明有公共焦点哦,非父子关系
					
				//var_dump($merge);
				$tmp=Document::find($merge['id']);
				$ver =$tmp->latest_version();
				if (!$ver) {
					return json_encode(array(
	                    'children' => array()
					));
				}
				switch ($tmp->type) {

					case 'rs':
						if(!$rsitem||count($this->array_column($this->tags,'id'))<=0){$items=[];break;}
						$middles =Rs::where('version_id','=', $ver->id)->whereIn('id',$this->array_column($this->tags,'id'))->get();
						break;
					case 'tc':
						if(!$rsitem||count($this->array_column($this->tags,'id'))<=0){$items=[];break;}
						$middles =Tc::where('version_id','=',$ver->id)->whereIn('id',$this->array_column($this->tags,'id'))->get();
						break;
					default:
						$middles= array();
				}//switch
				$this->tags=[];
				foreach($middles as $key=>$value){
					//只可能往下走
				 $this->getTags_down($value);
				}
			}//else

			switch ($docs->type) {
				case 'rs':
					if(count($this->array_column($this->tags,'id'))<=0){$items=[];break;}
					$items =Rs::where('version_id','=', $version->id)->whereIn('id',$this->array_column($this->tags,'id'))->get();
					break;
				case 'tc':
					if(count($this->array_column($this->tags,'id'))<=0){$items=[];break;}
					$items =Tc::where('version_id','=',$version->id)->whereIn('id',$this->array_column($this->tags,'id'))->get();
					break;
				default:
					$items = array();
			}
			foreach($items as $v) {
				$rt[] = array(
                    'name' => $v->tag,
                    'leaf' => true,
                    'id' => $v->id,
                    'item_id' => $v->id,
                    'type' => $docs->type
				);
			}
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

