<?php
use Illuminate\Support\Facades\Input;

class ProjectController extends BaseController {
	// 项目创建
	public function store() {
		$project = new Project ( Input::get () );
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
				$ret [$key] = $value;
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
			$old_version=Version::where('document_id','=',Input::get( 'document_id'))->orderBy('updated_at','desc')->first();
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
		$version->regrex=$regrex;
		$version->touch();
		$version->save ();
		$url_path = public_path () . '/files/' . $name;
		//存贮列名到version里面的headers
		$column = Input::get ("column");
		$cols=explode(',',$column);$no_col=[];
		foreach($cols as $col){array_push($no_col,$col);}
		$version->headers=strtolower(implode(',',$no_col));//此时column还没有过滤
		//$version->save();

		try{
			/*	$soap = new SoapClient ( "http://localhost:2614/WebService2.asmx?WSDL" );
			 //$result2 = $soap->test( array ('url'=>'123' ) );
			 $result2 = $soap->resolve ( array ('column' => $column, 'type' => $type, 'doc_url' => $doc_url ) );
			 */
			$type = Input::get ( "type" );
			//$doc_url = 'http://127.0.0.1/casco-api/public/files/' . $name;
			$doc_url='http://192.100.212.31:8080/files/'.$name;
			//$u ='http://192.100.212.33/WebService2.asmx/resolve?doc_url='.$doc_url.'&column='.urlencode($column).'&type='.$type;
			$u ='http://192.100.212.33/WebService2.asmx/resolve?doc_url='.$doc_url.'&column='.urlencode($column).'&type='.$type.'&regrex='.urlencode(urlencode($regrex));
			$this->v();
			$result2 = file_get_contents($u);
			$add = 0;
			$modify = 0;
			$wait_save = array ();

			if (!json_decode($result2)){
				//$this->p();//carefully
				throw new Exception();
				$version->result="读取文档失败，远程服务器返回结果:".$result2;
				$version->save($result2);
				return array ('success' => false, 'msg' =>$version->result);
			}
			$resolveResult =$this->objtoarr(json_decode($result2));
			//var_dump($resolveResult);exit();
			if (Input::get ( 'type' ) == 'tc') {
				foreach ($resolveResult as $value ) {

					$num = Tc::where ( "tag", "=", $value['tag'] )->where ( "version_id", "=", $version->id )->first ();

					if ($num) {
						$num->column = "";
						foreach ($value as $key => $item ) {
							if ($key != 'tag' && $key != 'test steps') {
								$num->column .= '"'.strtolower(trim($key)) . '":"' .  addslashes(trim($item)) . '",';
							} else if ($key == 'test steps') {
								//做相应的处理哦
								$wait_save = json_decode ( $item,true )?json_decode ( $item,true ):array();

							} //else if
						}
						$num->column = substr ( $num->column, 0, - 1 );
						$num->save ();
						if ($wait_save) {
							$num->steps()->delete();

						}
						foreach ( $wait_save as $value ) {
							$in = array ();
							$in ["tc_id"] = $num->id;
							$in = array_merge ( $in, (array)$value );
							$step = TcStep::create ( $in );

						}
						$modify ++;
						///	return "更新记录";

					} else { //不存在此记录了
						$tc = new TC ( );

						foreach ((array)$value as $key => $item ) {

							if ($key != 'tag' && $key != 'test steps') {
								$tc->column .= '"'.strtolower(trim($key)) . '":"' .  addslashes(trim($item)) . '",';
							} else if ($key == 'test steps') {
								//做相应的处理哦
								$wait_save =json_decode ( $item,true )?json_decode ( $item,true ):array();

							} else if ($key == 'tag') {

								$tc->tag = $item;
							}
						} //foreach
						$tc->column = substr ( $tc->column, 0, - 1 );
						$tc->version_id = $version->id ;
						$tc->save ();
						foreach ( $wait_save as $value ) {
							$in = array ();
							$in ["tc_id"] = $tc->id;
							$in = array_merge ( $in, (array)$value );
							//	var_dump($in);
							$step = TcStep::create ( $in );
							//$step->save(); //这一句有问题么
						}

						$add ++;

						//	return "增添记录";

					}

				} //foreach
			} //if


			if (Input::get ( 'type' ) == 'rs') {

				foreach ($resolveResult as $value ) {

					$rs = Rs::where ( "tag", "=", $value['tag'] )->where ( "version_id", "=", $version->id  )->first ();
					if ($rs) {
						$rs->column='';
						foreach ( $value as $key => $item ) {

							if ($key != 'tag') {

								$rs->column .=  '"'.strtolower(trim($key)) . '":"' .  addslashes(trim($item)) . '",';
							}
						}
						$rs->column = substr ( $rs->column, 0, - 1 );
						$rs->save ();
						$modify++;

					} else { //不存在此记录了
						//原来的tag为空的version_id的rs要不要删除掉
						$rs = new RS ( );
						foreach ( $value as $key => $item ) {

							if ($key != 'tag') {
								$rs->column .= '"'.strtolower(trim($key)) . '":"' .  addslashes(trim($item)) . '",';
							}else{

								$rs->tag=$item;
							}
						} //foreach
						$rs->column = substr ( $rs->column, 0, - 1 );
						$rs->version_id = $version->id ;
						$rs->save ();
						$add++;


					}//else
				} //foreach
			} //if rs

			$adds=array();$updates=array();$nochange=array();$delete=array();
			//获取新的值出来进行比较,有问题逻辑错乱了,因为会把旧的新的一股脑dump出来
			// $new_array=Input::get('type')=="rs"?Version::find($version->id)->rss->toArray():Version::find($version->id)->tcs->toArray();
			$new_array=$resolveResult;
			//var_dump($new_array);
			foreach($old_array  as $old){

				foreach($new_array  as $new){

					if($old['tag']==$new['tag']){

						if(!$std=json_decode('{'.$old['column'].'}')){


							//var_dump("hehhe".$old['column']);

							array_push($updates,$old['tag']);

							break;

						}
						$olds=$this->objtoarr($std);

						//注意此函数tag在前才行哦
						$news=array_slice($new,1);
						$news=array_map('trim',$news);
						// var_dump(array_diff($olds,$news));
						//  var_dump(array_diff($news,$olds));
						//这个函数有问题哇
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
			//   var_dump($old_tag); var_dump($new_tag);
			$delete=array_diff($old_tag,$new_tag);
			$adds=array_diff($new_tag,$old_tag);
			function filter_self($item){

				preg_match('/(\[[^\]]*?\])/',$item,$matches);
				return $matches[1];
			}
			//var_dump($delete);var_dump($adds);
			/*删除delete项目?新建的就不删,重复的就删除
			if(Input::get ( 'isNew' )==0&&$delete=null){
			echo DB::table(Input::get('type')=="rs"?'Rs':'Tc')->whereIn('tag',($delete))->toSql();
			return ;
			$items=DB::table(Input::get('type')=="rs"?'Rs':'Tc')->whereIn('tag',($delete))->delete();

			}
			$items=Input::get ('type')=="rs"?(Rs::where("tag", "in", $delete)->toSql()):(Tc::where("tag", "in", $delete)->toSql());
			var_dump($items);exit();
			foreach($items as $item ){

			$item->delete();
			}
			*/
			//要不要做个显示报表啊更直观一些.
	$result='<table border=”1″ cellspacing=”0″ cellpadding=”2″>
	<tr><td colspan="2"  align=center>旧版本'.$old_version->name.';新版本'.$version->name.'</td></tr>
	<tr><td><font size="3" color="#00FF00">增添'.count($adds).'条</font></td><td>'.(string)implode(',',$adds).'</td></tr>
	<tr><td><font size="3" color="blue">修改'.count($updates)."条</font></td><td>".(string)implode(',',$updates).'</td></tr>
	<tr><td><font size="3" color="red">遗留(删除)'.count($delete)."条</font></td><td>".(string)implode(',',$delete).'</td></tr>
	<tr><td><font size="3" color="#FF8C00">未变(tag)'.count($nochange)."条</font></td><td>".(string)implode(',',$nochange).'
	</td></tr></table>';
			$version->result=$result;
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
