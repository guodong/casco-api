<?php
use Illuminate\Support\Facades\Input;
class ProjectController extends BaseController {
	// 项目创建
	public function store() {
		$project = new Project ();
		$project->name = Input::get('name');
		$project->description = Input::get('description');
		$project->save ();
		//表单额外的数据的接收方式哦
		foreach ( Input::get ( 'participants' ) as $v ) {
			$project->participants ()->attach ( $v ['id'] );
		}
		foreach ( Input::get ( 'vatstrs' ) as $v ) {
			$vatstr = new Vatstr ( $v );
			$project->vatstrs ()->save ( $vatstr );
		}
		return $project;
	}
	
	public function destroy($id){
		
		$project=Project::find($id);
		if(!$project)  return [];
		foreach($project->documents as $doc){
			$doc->delete();
		}
		foreach($project->testjobtmps as  $test){
			$test->delete();
		}
		foreach($project->verifications as $ver){
			$ver->delete();
		}
		foreach($project->testjobs as $testjob){
			$testjob->delete();
		}
		foreach($project->vatstrs as $vat){
			$vat->delete();
		}
		$project->participants()->detach();// as $pa){
		$project->delete();
		return $project;
		
		
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
	public function objtoarr($obj) {
		$ret = array ();
		foreach ($obj as $key => $value ) {
			if (gettype ( $value ) == 'array' || gettype ( $value ) == 'object') {
				$ret [$key] = $this->objtoarr ( $value );
			} else {
				$ret [$key] = ($value);
			}
		}
		return $ret;
	}
	public function docfile_pre() {
		$signal=Signal::first()->task;
		//var_dump($signal);
		return $signal;
	}
	public function p(){
		if(Signal::first()->task>0)Signal::decrement('task');
	}
	public function v(){
		while(Signal::first()->task>1)sleep(60);//休眠
		Signal::increment('task');//注意顺序很重要
	}
	
	public function docfile() {
		
		
		if (Input::get ( 'isNew' ) == 1){
			$old_version=Version::where('document_id',Input::get( 'document_id'))->orderBy('updated_at','desc')->first();
			if(!$old_version) $old_array = [];
			else $old_array=Input::get('type')=="rs"?$old_version->rss->toArray():$old_version->tcs->toArray();
			$version = Version::create ( array ('name' => Input::get ( 'name' ), 'document_id' => Input::get ( 'document_id' ) ) );
		}
		else{
			$version = Version::find (Input::get ( 'version_id' ));
			$old_version=$version;
			$old_array=Input::get('type')=="rs"?$old_version->rss->toArray():$old_version->tcs->toArray();
		}
		//不显示出来的列名,白名单的处理也应该在后端处理
		set_time_limit ( 0 );
		$name = uniqid () . '.doc';
		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], public_path () . '/files/' . $name );
		$version->filename = $name;
		$regrex=Input::get("regrex")?Input::get("regrex"):null;
		$ismerge=Input::get("ismerge")?Input::get("ismerge"):null;
		$version->regrex=$regrex;
		$version->touch();
		$version->save ();
		$url_path = public_path () . '/files/' . $name;
		//存贮列名到version里面的headers
		$column = Input::get ("column");$problems=[];
		//不要弄混切记啊
		$cols=explode(',',$column);$no_col=[];$no_cols=[];global $black;
		foreach($cols as $col){array_push($no_cols,($col));if(!in_array($col,$black))array_push($no_col,($col));}
		$version->headers=strtolower(implode(',',$no_cols));//此时column还没有过滤
		try{
			/*	$soap = new SoapClient ( "http://localhost:2614/WebService2.asmx?WSDL" );
			 $result2 = $soap->resolve ( array ('column' => $column, 'type' => $type, 'doc_url' => $doc_url ) );
			 */
			$type = Input::get ( "type" );
			$doc_url = 'http://127.0.0.1/casco-api/public/files/' . $name;
			//$u ='http://192.100.212.33/WebService2.asmx/resolve?doc_url='.$doc_url.'&column='.urlencode($column).'&type='.$type;
			$u ='http://localhost:2614/WebService2.asmx/resolve?doc_url='.$doc_url.'&column='.urlencode($column).'&type='.$type.'&ismerge='.$ismerge.'&regrex='.urlencode(urlencode($regrex));
			$this->v();
			$result2 = file_get_contents($u);
			$add = 0;
			$modify = 0;
			$wait_save = array ();
			if (!json_decode($result2)){
				throw new Exception($result2);
				$version->result="读取文档失败，远程服务器返回结果:".$result2;
				$version->save($result2);
				return array ('success' => false, 'msg' =>$version->result);
			}
			$resolveResult =$this->objtoarr(json_decode($result2));
			if (Input::get ( 'type' ) == 'tc') {
				foreach ($resolveResult as $value ) {
					$num = Tc::where ( "tag", "=", $value['tag'] )->where ("version_id", "=", $version->id )->first ();
					$arr=array_keys(array_change_key_case($value,CASE_LOWER));
					//var_dump($no_col,$arr,array_diff($no_col,$arr));
					if(count(array_diff($no_col,$arr))>0)array_push($problems,($value['tag'].'=>'.implode(',',array_diff($no_col,$arr))));				
					if ($num) {
						$num->column = json_encode($value);			
						foreach ($value as $key => $item ) {
							if ($key == 'test steps') {
								//做相应的处理哦
								$wait_save = json_decode ( $item,true )?json_decode ( $item,true ):array();
							} //else if
						}
						$num->save ();
						if ($wait_save) {
							$num->steps()->delete();
						}$i=1;
						foreach ( $wait_save as  $value ) {
							$in = array ();$value=(array)$value;$in['num']=$i++;
							$in ["tc_id"] = $num->id;
							$in ['actions']=array_key_exists('testing steps',$value)?$value['testing steps']:null;
							$in = array_merge ( $in, (array)$value );
							$step = TcStep::create ( $in );
							//$step->save(); //这一句有问题么
						}
						$modify ++;
						///	return "更新记录";
					} else { //不存在此记录了
						$tc = new TC ( );$tc->column=json_encode($value);
						$tc->tag=$value['tag'];
						foreach ((array)$value as $key => $item ) {
							 if ($key == 'test steps') {
								//做相应的处理哦
								$wait_save =json_decode ( $item,true )?json_decode ( $item,true ):array();
							} 							
						} //foreach
						$tc->version_id = $version->id ;
						$tc->save();$i=1;
						foreach ( $wait_save as  $value ) {
							$in = array ();$value=(array)$value;$in['num']=$i++;
							$in ["tc_id"] = $tc->id;
							$in ['actions']=array_key_exists('testing steps',$value)?$value['testing steps']:null;
							$in = array_merge ( $in, (array)$value );
							$step = TcStep::create ( $in );						}
						/*foreach ( $wait_save as  $value ) {
							$in = array ();$value=(array)$value;$in['num']=$i++;
							$in ["tc_id"] = $tc->id;
							$in ['actions']=array_key_exists('testing steps',$value)?$value['testing steps']:null;
							$in = array_merge ( $in, (array)$value );
							$step = TcStep::create ( $in );
							//$step->save(); //这一句有问题么
						}*/
						$add ++;
						//	return "增添记录";
					}
				} //foreach
			} //if
				
			if (Input::get ( 'type' ) == 'rs') {
				foreach ($resolveResult as $value ) {
					$rs = Rs::where ( "tag", "=", $value['tag'] )->where ( "version_id", "=", $version->id  )->first ();
					$arr=array_keys(array_change_key_case($value,CASE_LOWER));
					if(count(array_diff($no_col,$arr))>0)array_push($problems,($value['tag'].'=>'.implode(',',array_diff($no_col,$arr))));							if ($rs) {
						$rs->column=json_encode($value);
						$rs->save ();
						$modify++;
					} else { //不存在此记录了
						//原来的tag为空的version_id的rs要不要删除掉
						$rs = new RS ( );$rs->tag=$value['tag'];
						$rs->column=json_encode($value);
						$rs->version_id = $version->id ;
						$rs->save ();
						$add++;
					}//else
				} //foreach
			} //if rs
			$adds=array();$updates=array();$nochange=array();$delete=array();
			$new_array=$resolveResult;
			foreach($old_array  as $old){
				foreach($new_array  as $new){
					if($old['tag']==$new['tag']){
						if(!$std=json_decode($old['column'])){
							array_push($updates,$old['tag']);
							break;
						}
						$olds=$this->objtoarr($std);
						$news=array_slice($new,1);
						$news=array_map('trim',$news);
						if(array_diff($olds,$news)==null&&array_diff($news,$olds)==null){
							//说明此时并未修改;
							array_push($nochange,$old['tag']);
						}else{
							array_push($updates,$old['tag']);
						}
						break;
					}
					continue;
				}
			}
			$old_tag=$this->array_column(($old_array),'tag');
			$new_tag=$this->array_column(($new_array),'tag');
			$delete=array_diff($old_tag,$new_tag);
			$adds=array_diff($new_tag,$old_tag);
			function filter_self($item){
				preg_match('/(\[[^\]]*?\])/',$item,$matches);
				return $matches[1];
			}
			/*删除delete项目?新建的就不删,重复的就删除
			if(Input::get ( 'isNew' )==0&&$delete=null){
			echo DB::table(Input::get('type')=="rs"?'Rs':'Tc')->whereIn('tag',($delete))->toSql();
			return ;
			$items=DB::table(Input::get('type')=="rs"?'Rs':'Tc')->whereIn('tag',($delete))->delete();
			}
			*/
			//删除原来剩余的
			$del_items=Input::get ('type')=="rs"?(Rs::where('version_id',$old_version?$old_version->id:null)->whereIn("tag",$delete)->get()):(Tc::where('version_id',$old_version?$old_version->id:null)->whereIn("tag", $delete)->get());
			foreach($del_items as $item ){
			$item->delete();
			}
			//要不要做个显示报表啊更直观一些.
	$result='<table border=”1″ cellspacing=”0″ cellpadding=”2″>
	<tr><td colspan="2"  align=center>旧版本'.($old_version?$old_version->name:null).';新版本'.$version->name.'</td></tr>
	<tr><td><font size="3" color="#00FF00">增添'.count($adds).'条</font></td><td>'.(string)implode(',',$adds).'</td></tr>
	<tr><td><font size="3" color="blue">修改'.count($updates)."条</font></td><td>".(string)implode(',',$updates).'</td></tr>
	<tr><td><font size="3" color="red">遗留(删除)'.count($delete)."条</font></td><td>".(string)implode(',',$delete).'</td></tr>
	<tr><td><font size="3" color="#FF8C00">未变(tag)'.count($nochange)."条</font></td><td>".(string)implode(',',$nochange).'</td></tr>
	<tr><td><font size="3" color="blue">解析列名失败'.count($problems)."条</font></td><td>".(string)implode(',',$problems).'</td></tr></table>';
			$version->add=count($adds);$version->modify=count($updates);$version->broken=$problems;
			$version->unchanged=count($nochange);$version->left=count($delete);
			$version->result=$result;
			$version->export_result=json_encode(array(
			array('增添'.count($adds).'条'=>(string)implode(',',$adds)),
			array('修改'.count($updates).'条'=>(string)implode(',',$updates)),
			array('遗留(删除)'.count($delete).'条'=>(string)implode(',',$delete)),
			array('未变(tag)'.count($nochange).'条'=>(string)implode(',',$nochange)),
			array('解析列名失败'.count($problems).'条'=>(string)implode(',',$problems))
			));
			$version->save();
			$this->p();
			return  array('success'=>true,"msg"=>$result);
		}catch (SoapFault $e ) {
			$this->p();
			$version->result=json_encode(array ('success' => false, 'msg' => $e->getMessage () ));
			$version->save();
			return array ('success' => false, 'msg' => $e->getMessage ());
		} catch ( Exception $e ) {
			$this->p();
			if($version){
				$version->result=json_encode(array ('success' => false, 'msg' => $e->getMessage ()));
				$version->save();
			}
			return array ('success' => false, 'msg' => $e->getMessage () );
		}
	}
	// 项目列表
	public function index() {
		if (Input::get ( 'user_id' )) {
			$user = User::find ( Input::get ( 'user_id' ) );
			if ($user) {
				$projects = $user->projects;
			} else {
				$projects = null;
			}
		} else {
			$projects = Project::all ();
		}
		if ($projects) {
			$projects->each(function ($v)
			{
				$v->participants;
				$v->vatstrs;
			})
			;
		}
		return $projects;
	}
	public function show($id) {
		if(!$id||$id=='null'){$p=Project::orderBy('updated_at','desc')->first();}
		else{$p=Project::find ( $id );}
		if(!$p)return [];
		$graph=json_decode($p->graph);
		$cells=$graph?$graph->cells:[];$new_graph=array();
		foreach($cells as $key=>$node){
		if ($node->type == 'basic.Rect'){
			$doc=Document::find($node->id);
			//var_dump($graph->cells[$key]->attrs->text->text='123');
			if($doc){
				//$graph->cells[$key]->attrs->text->text=$doc->name;
				$new_node=$node;
				$new_node->attrs->text->text=$doc->name;
				$new_graph[]=$new_node;
			}else{//说明此文档被删除掉了,应该更新一下
			   continue;
			}
		}else if($node->type == 'fsa.Arrow'){//连线属性(also judge)
			if(Document::find($node->source->id)&&Document::find($node->target->id))
			$new_graph[]=$node;
		}
		}//foreach
		$p->graph=json_encode(array('cells'=>$new_graph));
		$p->save();
		$p->documents;
		return $p;
	}
	public function update($id) {
		$project = Project::find ( $id );
		$data = Input::get ();
		if (! empty ( $data ['graph'] )) {
			$graph = json_decode ( $data ['graph'] );
			foreach ( $graph->cells as $node ) {
				if ($node->type != 'basic.Rect')
				continue;
				$src = Document::find($node->id);
				if($src)$src->dests()->detach();
			}//foreach
			foreach ($graph->cells as $node) {
				if ($node->type != 'fsa.Arrow')
				continue;
				$src = Document::find($node->source->id);
				$dst = Document::find($node->target->id );
				if($src)$src->dests()->attach($dst);
			}//foreach
		}
		$project->update ( $data );
		if (Input::get ( 'participants' )) {
			$ids = array ();
			foreach ( Input::get ( 'participants' ) as $v ) {
				$ids [] = $v ['id'];
			}
			$project->participants ()->sync ( $ids );
		}
		// vatstr的修改处理可能有问题，当有的tc已经指派了vatstr，下面的操作会删掉全部vatstr重新添加，修改了vatstr的id，导致tc的vatstr索引野指针
		if (Input::get ( 'vatstrs' )) {
			$project->vatstrs ()->delete ();
			foreach ( Input::get ( 'vatstrs' ) as $v ) {
				$vatstr = new Vatstr ( $v );
				$project->vatstrs ()->save ( $vatstr );
			}
		}
		return $project;
	}
}