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
		$column = Input::get('column');
		
		$target_url = 'http://192.100.110.87:2614/webservice2.asmx/resolve';
		if (Input::get('isNew') == 1) {
			$old_version = Version::where('document_id', Input::get('document_id'))->orderBy('updated_at', 'desc')->first();
			if (!old_version)
				$old_array = [];
			else 
				$old_array = Input::get('type') == 'rs' ? $old_version->rss->toArray() : $old_version->tcs->toArray();

			$version = Version::create(array ('name' => Input::get ( 'name' ), 'document_id' => Input::get ( 'document_id' ) );
		} else {
			$version = Version::find (Input::get ( 'version_id' ));
			$old_version=$version;
			$old_array=Input::get('type')=="rs"?$old_version->rss->toArray():$old_version->tcs->toArray();
		}
		$filename = uniqid() . '.doc';
		$version->filename = $filename;
		$version->save();

		$realpath = realpath(public_path() . '/files/' . $filename);
		move_uploaded_file($_FILES["file"]["tmp_name"], $realpath);
		
		$cFile = '@' . realpath($realpath);
		$post = array('postData'=> $cFile, 'column' => $column, 'ismerges' => $ismerge, 'regrex' => $regrex, 'type' => $type);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$target_url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		curl_close($ch);
		$new_array = json_decode($result);
		if (!$new_array) {
			return array ('success' => false, 'msg' => 'windows parse error');
		}

		$addedc = $updatedc = $deletedc = $unupdatedc = $failedc = 0;
		$added = $updated = $deleted = $unupdated = $failed = [];
		if (count($old_array) === 0) {
			$added = $new_array;
			$addedc = count($new_array);
		} else {
			$added = array_udiff($new_array, $old_array, 'compare'); //in new not in old
			$addedc = count($added);

			$deleted = array_udiff($old_array, $new_array, 'compare'); //in old not in new
			$deletedc = count($deleted);

			if (self::array_equal($old_array[0]['column'], explode($column))) {
				$updatedc = count($old_array) - $deletedc;
			} else {
				foreach ($old_array as $old_i) {
					foreach ($new_array as $new_i) {
						if ($old_i['tag'] == $new_i['tag']) {
							if (count(array_diff_assoc($old_i['column'], $new_i['column'])) !== 0)
								$updatedc++;
						}
					}
				}
			}
			foreach ($new_array as $new_i) {
				if (count($new_i['column']) < count($column))
					$failedc++;
			}

			$updatedc -= $failedc;

			$unupdatedc = count($old_array) - $updatedc - $deletedc - $failedc;
		}

		foreach ($new_array as $v) {
			if ($type == 'rs') {
				$rs = new RS();
				$rs->tag = $v['tag'];
				$rs->column = json_encode($v['column']);
				$rs->version_id = $version->id;
				$rs->save();
			} else {
				$tc = new TC();
				$tc->column = json_encode($v['column']);
				$tc->tag = $v['tag'];
				$tc->version_id = $version->id;
				$tc->save();
				$i = 1;
				foreach ($v['test steps'] as $k => $vv) {
					$step = new TcStep();
					$step->tc_id = $tc->id;
					$step->num = $i++;
					$step->actions = empty($vv['testing steps']) ? null : $vv['testing steps'];
					$step->save();
				}
			}
		}

		$modarr = array_merge($deleted, $updated);
		foreach ($modarr as $v) {
			if ($type == 'tc')
				$v->steps()->delete();
			$v->delete();
		}


		
		return array(
				'added' => $addedc,
				'updated' => $updatedc,
				'deleted' => $deletedc,
				'unupdated' => $unupdatedc,
				'failed' => $failedc
				);

	}

	static function compare($a, $b)
	{
		if ($a->tag === $b->tag)
			return 0;
		return 1;
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
