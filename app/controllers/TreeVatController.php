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
	private function getTags_down($item)
	{
		//if(!$item||!$item->verison||!$item->version->document){echo 'error';var_dump($item);echo 'shit';var_dump($item->version);return;}
		$sss=$item->srcs();
		foreach ($sss as $v){
			if ($v&&!in_array($v->toArray(), $this->tags)){
				$tmp=$v->toArray();$tmp['mark']='down';
				$this->tags[] = $tmp;
				$this->getTags_down($v);
			}
		}
	}

	private function getTags_up($item)
	{
		    if(!$item)return;
			$sss=$item->dests();
			//var_dump($sss);
			foreach ($sss as $v){
				if ($v&&!in_array($v->toArray(), $this->tags)){
					$tmp=$v->toArray();$tmp['mark']='up';
					$this->tags[] = $tmp;
					$this->getTags_up($v);
				}
			}
	}

	public function array_column($input,$column_key,$index_key=''){

		if(!is_array($input)) return;
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

	function current_doc($items){
		$arr=[];
		while(!$item=array_shift($items)){
			if($item&&$item->version->document->id==$this->foder_id){
				$arr[]=$item;
			}else{continue;}
		}
		return  $arr;
	}
	/*
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
	 */
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
					$rsitem = Rs::find(Input::get('rs_id'));
					if(!$rsitem){$items=[];break;}
					$this->getTags_down($rsitem);
					$this->getTags_up($rsitem);
					//var_dump($this->array_column($this->tags,'id'));
					$items =Rs::where('version_id', $version->id)->whereIn('id',$this->array_column($this->tags,'id'))->get();
					break;
				case 'tc':
					$rsitem = Rs::find(Input::get('rs_id'));
					if(!$rsitem){$items=[];break;}
					$this->getTags_down($rsitem);
					$this->getTags_up($rsitem);
					$items =Tc::where('version_id', $version->id)->whereIn('id',$this->array_column($this->tags,'id'))->get();
					/*Tc::where('version_id','=', $version->id)->where(function ($query)use($rsitem){
					 $query->orWhere('column', 'like', '%"source":%' . $rsitem->tag . '%');
					 foreach ($this->tags as $v){
					 $query->orWhere('column', 'like', '%"source":%' . $v . '%');
					 }
					 });*/
					break;
				default:
					$items = array();
			}
			//var_dump($this->tags);
			foreach($items as $v) {
				$rt[] = array(
                    'name' => $v->tag,
                    'leaf' => true,
                    'id' => $v->id,
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

