<?php
use Illuminate\Support\Facades\Input;

class DocumentController extends Controller
{

    private function calresult($rs)
    {
        $v = $rs;
        $result = 1;
        $v->vat;
        $v->vatstr;
        if (count($v->tcs()) == 0 && $v->vatstr_result == 0) {
            return 0;
        }
        foreach ($v->tcs() as $vv) {  
            if ($vv->result == 2) {
                $result = 2;
                break;
            } elseif ($vv->result == 0) {  
                $result = 0;
            }
        }
        if ($result == 1) {
            if ($v->vatstr_result == 2) {
                $result = 2;
            }
        }
        return $result;
    }

    public function index()
    {   
    	
       if(!Input::get('project_id')&&!Input::get('mode')){
           return  DB::table('document')->join('project','document.project_id','=','project.id')->select('document.project_id as project_id','project.name as project_name','document.id as document_id','document.name as document_name','document.type as document_type')->get();
       }
        $docs =Document::where('project_id', '=', Input::get('project_id'));
        if (Input::get('type')){
            $docs = $docs->where('type', '=', Input::get('type'))->get();
            foreach ($docs as $v){
                $v->versions;
            }
            return $docs;
        }
        if(Input::get('mode') == 'related'){
            $doc = Document::find(Input::get('document_id'));
            $docs = $doc->dests;
            foreach ($docs as $v){
                $v->versions;
            }
            return $docs;
        }
        else if(Input::get('mode') == 'related'){
            $doc = Document::find(Input::get('document_id'));
            $docs = $doc->dests;
            foreach ($docs as $v){
                $v->versions;
            }
            return $docs;
        }
        $docs = $docs->get();
        foreach($docs as $v){
            $v->versions;
        }
        return $docs;

    }
    
    public function show($id)
    {    
        $document =Document::find($id);
        $document->versions;
        return $document;
    }

    public function store()
    {
        $document = new Document(Input::get());
        $document->save();
        return $document;
    }

    public function update($id)
    {  
    	$document =Document::find($id);
        $document->update(Input::get());
        $document->save();
        return $document;
    }

    public function destroy($id)
    {
      $document=Document::find($id);
       //delete project->graph
       $project=$document->project;
       $graph=json_decode($project?$project->graph:null);
       $data=[];
       if($graph){
         foreach($graph->cells  as $nodes)
         {  
          if($nodes->type=='basic.Rect'&&$nodes->id==$id)
          {
             continue;
          }elseif($nodes->type=='fsa.Arrow'&&($nodes->source->id==$id||$nodes->target->id==$id)){
                 continue;
           }else{
           $data[]=$nodes;
         }
      
         }//foreach
       
        }//if
     //udate  project graph
       
        $graph->cells=($data);
       // var_dump(json_encode($graph));       
        $project->graph=json_encode($graph);
        $project->save();


       
       foreach($document->versions  as  $vs){
         foreach($vs->tcs as $tcs){
              $tcs->delete();
         }
        foreach($vs->rss as $rss){
              $rss->delete();
         }
           Version::destroy($vs->id);//删除versions

       }
		$r = Relation::where('src', '=', $id);
		$r->delete();
		$r = Relation::where('dest', '=', $id);
		$r->delete();
		foreach($document->subs  as $subs){
			$subs->delete();//子文件的删除
		}
		$document->destroy($id);
  
		return $document;

    }
    public function version()
    {
        $doc = Document::find(Input::get('document_id'));
        if ($doc->type = 'rs')
            $vs = Rs::distinct()->get(array(
                'version'
            ));
        else
            $vs = Tc::distince()->get(array(
                'version'
            ));
        $versions = [];
        foreach ($vs as $v) {
            if ($v->version == '') {
                continue;
            }
            $versions[] = $v;
        }
        return $versions;
    }

	public function import()
	{
		$regrex = Input::get("regrex") ? Input::get("regrex") : '';
		$ismerge = Input::get("ismerge") ? Input::get("ismerge") : 0;
		$type = Input::get('type');
		$column = strtolower(Input::get('column'));
		$isnew = Input::get('isNew') ? true : false;

		// $fileapi = 'http://192.100.110.96:8000/';
        // $target_url = 'http://192.100.110.96:8500/parse';
        
		$fileapi = 'http://localhost/';
        $target_url = 'http://localhost:9760/WebService1.asmx/InputWord';
        
		if (Input::get('isNew') == 1) {
			$old_version = Version::where('document_id', Input::get('document_id'))->orderBy('updated_at', 'desc')->first();
			if (!$old_version)
				$old_array = [];
			else 
				$old_array = Input::get('type') == 'rs' ? $old_version->rss->toArray() : $old_version->tcs->toArray();

			$version = Version::create(array('name' => Input::get('name'), 'document_id' => Input::get('document_id')));
		} else {
			$version = Version::find(Input::get('version_id'));
			$old_version = $version;
			$old_array = Input::get('type')=="rs"?$old_version->rss->toArray():$old_version->tcs->toArray();
		}

        $version->filename = Input::get('filename');
        $version->headers = $column;
        $version->save();

        $post = array('file' => $fileapi.Input::get('filename'),
        'url' => $fileapi.Input::get('filename') ,
         'column' => $column,
          'ismerges' => $ismerge, 
          'regrex' => $regrex,
           'type' => $type);
		$query = http_build_query($post, '', '&');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$target_url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
        curl_close($ch);
    
        //$result='[{"title":"[TSP-SyAD-0314]","tag":"[TSP-SyAD-0314]","description":"Humidity condition shall obey the related requirement in EN 50125-3 .Relative humidity: ≤90% (no condensation) (25℃).\r\nHumidity condition shall obey the related requirement in EN 50125-3 .Relative humidity: ≤90% (no condensation) (25℃).\r\n湿度条件应遵循环境标准EN 50125-3相关需求，相对湿度：≤90%（无凝结）（25℃）；\r","Source":["[TSP-SyRS-0031]"],"othercontext":"1","Implement":"","Priority":"","Contribution":" Safety\r","Category":" Non-Functional\r","Allocation":" [COTS]\r","sources":" [TSP-SyRS-0031]\r"},{"title":"[TSP-SyAD-0017]","tag":"[TSP-SyAD-0017]","description":"MPS shall provide safety clock. The variation (fast/slow) of safety clock shall be less than or equal to 0.1%. If the variation of the safety clock is larger than this value, MPS shall be guided to safety side.\r\nMPS shall provide safety clock. The variation (fast/slow) of safety clock shall be less than or equal to 0.1%. If the variation of the safety clock is larger than this value, MPS shall be guided to safety side.\r\nMPS应提供安全时钟，安全时钟偏差不大于等于0.1%。如检测安全时钟偏差大于该值，MPS导向安全。\r","Source":["[TSP-SyRS-0113]","[TSP-IHA-0020]"],"othercontext":"2","Implement":"","Priority":"","Contribution":" SIL4\r","Category":" Functional\r","Allocation":" [MPS]\r","sources":" [TSP-SyRS-0113], [TSP-IHA-0020]\r"}]';
		$ret = json_decode($result);
		if (!$ret || !is_array($ret) && $ret->result != 0) {
			return array ('result' => 1, 'data' => 'parse api return error' . $result);
		}

		//$new_array = $ret->data;
        $new_array = $ret;
        $addedc = $updatedc = $deletedc = $unupdatedc = $failedc = 0;
		$added = $updated = $deleted = $unupdated = $failed = [];
		if (count($old_array) === 0) {
			$added = $new_array;
			$addedc = count($new_array);
		} else {

			$added = array_udiff($new_array, $old_array, array($this, 'compare')); //in new not in old
			$addedc = count($added);

			$deleted = array_udiff($old_array, $new_array, array($this, 'compare')); //in old not in new
			$deletedc = count($deleted);


            foreach ($old_array as $old_i) {
                foreach ($new_array as $new_i) {
                    if ($old_i['tag'] == $new_i->tag) {
                        if ($old_i['column'] != json_encode($new_i)) {
                            $updatedc++;
                            $updated[] = $new_i;
                        }
                    }
                }
            }



			$unupdatedc = count($old_array) - $updatedc - $deletedc;
		}

		if ($isnew) { // 新版本直接插入所有数据
		    foreach ($new_array as $v) {
                if ($type == 'rs') {
                    $rs = new RS();
                    $rs->tag = $v->tag;
                    $rs->column = json_encode($v);
                    $rs->version_id = $version->id;
                    $rs->save();
                } else {
                    $tc = new TC();
                    $tc->column = json_encode($v);
                    $tc->tag = $v->tag;
                    $tc->version_id = $version->id;
                    $tc->save();
                    $i = 1;
                    foreach ($v->test_steps as $vv) {
                        $step = new TcStep();
                        $step->tc_id = $tc->id;
                        $step->num = $i++;
                        $step->actions = empty($vv->actions) ? null : $vv->actions;
                        $step->expected_result = empty($vv->expected_result) ? null : $vv->expected_result;
                        $step->save();
                    }
                }
            }

		} else {

            foreach ($added as $v) {
                if ($type == 'rs') {
                    $rs = new RS();
                    $rs->tag = $v->tag;
                    $rs->column = json_encode($v);
                    $rs->version_id = $version->id;
                    $rs->save();
                } else {
                    $tc = new TC();
                    $tc->column = json_encode($v);
                    $tc->tag = $v->tag;
                    $tc->version_id = $version->id;
                    $tc->save();
                    $i = 1;
                    foreach ($v->test_steps as $vv) {
                        $step = new TcStep();
                        $step->tc_id = $tc->id;
                        $step->num = $i++;
                        $step->actions = empty($vv->actions) ? null : $vv->actions;
                        $step->expected_result = empty($vv->expected_result) ? null : $vv->expected_result;
                        $step->save();
                    }
                }
            }

            foreach ($deleted as $v) {
                if ($type == 'tc') {
                    $tc = TC::where('tag', $v['tag'])->where('version_id', $version->id)->first();

                    $tc->delete();
                } else {
                    $rs = RS::where('tag', $v['tag'])->where('version_id', $version->id)->first();
                    $rs->delete();
                }
            }

            foreach ($updated as $v) {
                if ($type == 'rs') {
                    $_rs = RS::where('tag', $v->tag)->where('version_id', $version->id)->get();
                    foreach($_rs as $r)
                        $r->forceDelete();

                    $rs = new RS();
                    $rs->tag = $v->tag;
                    $rs->column = json_encode($v);
                    $rs->version_id = $version->id;
                    $rs->save();
                } else {
                    $_tc = TC::where('tag', $v->tag)->where('version_id', $version->id)->get();
                    foreach($_tc as $r)
                        $r->forceDelete();

                    $tc = new TC();
                    $tc->column = json_encode($v);
                    $tc->tag = $v->tag;
                    $tc->version_id = $version->id;
                    $tc->save();
                    $i = 1;
                    foreach ($v->test_steps as $vv) {
                        $step = new TcStep();
                        $step->tc_id = $tc->id;
                        $step->num = $i++;
                        $step->actions = empty($vv->actions) ? null : $vv->actions;
                        $step->expected_result = empty($vv->expected_result) ? null : $vv->expected_result;
                        $step->save();
                    }
                }

            }
        }

        // 未修改的只做显示不做操作,所以独立出来,否则新版本unupdated为0
        foreach ($old_array as $old) {
            $has = false;
            foreach ($deleted as $del) {
                if ($del['tag'] == $old['tag']) {
                    $has = true;
                    break;
                }
            }
            if ($has) continue;
            foreach ($updated as $up) {
                if ($up->tag == $old['tag']) {
                    $has = true;
                    break;
                }
            }
            if ($has) continue;
            $unupdated[] = $old;
        }

		return array(
			'result' => 0,
			'data' => array(
				'added' => $added,
				'updated' => $updated,
				'deleted' => $deleted,
				'unupdated' => $unupdated
			)
		);

	}

	static function compare($a, $b)
	{
		$a = json_decode(json_encode($a),true);
		$b = json_decode(json_encode($b),true);
		if ($a['tag'] === $b['tag'])
			return 0;
		return ($a['tag']>$b['tag']) ? 1 : -1;
	}

	static function array_equal($a, $b)
	{
		return (
			is_array($a) && is_array($b) && 
			count($a) == count($b) &&
			array_diff($a, $b) === array_diff($b, $a)
		);
	}
}
