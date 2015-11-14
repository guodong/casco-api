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

	public function docfile() {

		if (Input::get ( 'isNew' ) == 1){
			$old_version=Version::where('document_id','=',Input::get( 'document_id'))->orderBy('updated_at','desc')->first();
			$version = Version::create ( array ('name' => Input::get ( 'name' ), 'document_id' => Input::get ( 'document_id' ) ) );
			 
		}
		else{
			$version = Version::find ( Input::get ( 'version_id' ) );
			$old_version=$version;
		}

		$old_array=Input::get('type')=="rs"?$old_version->rss->toArray():$old_version->tcs->toArray();
			
		// var_dump($old_array->toArray());
		//var_dump($this->objtoarr(($new_array)));
		//最后记得删除所有的表哦

		 
		set_time_limit ( 0 );
		$name = uniqid () . '.doc';
		//echo $name;
		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], public_path () . '/files/' . $name );
		$version->filename = $name;
		//先save一下方便后续更新?
		$version->save ();

		$url_path = public_path () . '/files/' . $name;
		//存贮列名到version里面的headers
		$column = Input::get ("column");
		$version->headers=strtolower($column);//此时column还没有过滤
		//$version->save();



		try {

			/*	$soap = new SoapClient ( "http://localhost:2614/WebService2.asmx?WSDL" );
			 //$result2 = $soap->test( array ('url'=>'123' ) );
			 	
			 	
			 $result2 = $soap->resolve ( array ('column' => $column, 'type' => $type, 'doc_url' => $doc_url ) );
			 // echo  "{success:true,info:'kaka!'}";
			 */          $type = Input::get ( "type" );
			$doc_url = 'http://127.0.0.1/casco-api/public/files/' . $name;
			$u ='http://localhost:2614/WebService2.asmx/resolve?doc_url='.$doc_url.'&column='.urlencode($column).'&type='.$type;
			$result2 = file_get_contents($u);

			$add = 0;
			$modify = 0;
			$wait_save = array ();
				
	  //$data = $this->objtoarr ( $result2 ); //返回tc文档的具体信息
			$resolveResult =$this->objtoarr(json_decode($result2));
			if (! $resolveResult) {
				$version->result="读取文档失败，请检查字段或配置".$u;
				$version->save();
				return $version->result;
			}
			//var_dump(($resolveResult));


			if (Input::get ( 'type' ) == 'tc') {
				foreach ( $resolveResult as $value ) {
						
					$num = Tc::where ( "tag", "=", $value['tag'] )->where ( "version_id", "=", $version->id )->first ();

					if ($num) {
						$num->column = "";
						foreach ( $value as $key => $item ) {

							if ($key != 'tag' && $key != 'test steps') {
								$num->column .= '"'.strtolower($key) . '":"' . $item . '",';
							} else if ($key == 'test steps') {
								//做相应的处理哦
								$wait_save = json_decode ( $item,true );

							} //else if
						}
						$num->column = substr ( $num->column, 0, - 1 );
						$num->save ();
						if ($wait_save) {

							$num->steps()->delete();
								
						}
						foreach ( $wait_save as $value ) {
							var_dump ( $value );
							$in = array ();
							$in ["tc_id"] = $num->id;
							$in = array_merge ( $in, $value );
								
							$step = TcStep::create ( $in );

						}
							
						$modify ++;
						///	return "更新记录";

					} else { //不存在此记录了
						$tc = new TC ( );
							
						foreach ( $value as $key => $item ) {

							if ($key != 'tag' && $key != 'test steps') {
								$tc->column .= '"'.strtolower($key) . '":"' . $item . '",';
							} else if ($key == 'test steps') {
								//做相应的处理哦
								$wait_save = json_decode ( $item, true );

							} else if ($key == 'tag') {
									
								$tc->tag = $item;
							}
						} //foreach
						$tc->column = substr ( $tc->column, 0, - 1 );
						$tc->version_id = $version->id ;
						$tc->save ();
						//再存储tc_steps
							

						// var_dump($wait_save);
						foreach ( $wait_save as $value ) {
							//TcStep::where ( "tc_id", "=", $tc->id )->drop ();
							//$value依然是一个数组
							//	 var_dump(array(json_decode($value)));
							// var_dump(json_decode($value,true));
							var_dump ( $value );
							$in = array ();

							$in ["tc_id"] = $tc->id;
							$in = array_merge ( $in, $value );
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
									
								$rs->column .=  '"'.strtolower($key) . '":"' . $item . '",';
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
								$rs->column .= '"'.strtolower($key) . '":"' . $item . '",';
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

						$olds=$this->objtoarr(json_decode('{'.$old['column'].'}'));

						//注意此函数tag在前才行哦
						$news=array_slice($new,1);
						//var_dump($news);
							
						//这个函数有问题哇
						if(array_diff_assoc($olds,$news)==null&&array_diff_assoc($news,$olds)==null){
								
								
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


			 
			$old_tag=array_column(($old_array),'tag');
			$new_tag=array_column(($new_array),'tag');
			//   var_dump($old_tag); var_dump($new_tag);
			$delete=array_diff($old_tag,$new_tag);
			$adds=array_diff($new_tag,$old_tag);
			var_dump($delete);var_dump($adds);
			//删除delete项目?新建的就不删,重复的就删除
			if(Input::get ( 'isNew' )==0)
			$items=DB::table(Input::get('type')=="rs"?'Rs':'Tc')->whereIn('tag',$delete)->delete();
		 /*  $items=Input::get ('type')=="rs"?(Rs::where("tag", "in", $delete)->toSql()):(Tc::where("tag", "in", $delete)->toSql());
		  var_dump($items);exit();
		  foreach($items as $item ){
		  	
		  $item->delete();
		  }
		  */

			 
			//要不要做个显示报表啊更直观一些.   
			$version->result='<table border=”1″ cellspacing=”0″ cellpadding=”2″>
		   <tr><td colspan="2"  align=center>旧版本'.$old_version->name.';新版本'.$version->name.'</td></tr>
		   <tr><td><font size="3" color="#00FF00">增添'.count($adds).'条</font></td><td>'.implode(',',$adds).'</td></tr>
		   <tr><td><font size="3" color="blue">修改'.count($updates)."条</font></td><td>".implode(',',$updates).'</td></tr>
	       <tr><td><font size="3" color="red">删除'.count($delete)."条</font></td><td>".implode(',',$delete).'</td></tr>
	      <tr><td><font size="3" color="#FF8C00">未变'.count($nochange)."条</font></td><td>".implode(',',$nochange).'
		   </td></tr></table>';
			$version->save();
		 return  array('success'=>true,"msg"=>$version->result);
		 	
		 	
		}catch ( SoapFault $e ) {
				
			$version->result=array ('success' => false, 'msg' => $e->getMessage () );
			$version->save();
			return array ('success' => false, 'msg' => $e->getMessage () );
		} catch ( Exception $e ) {
				
			$version->result=array ('success' => false, 'msg' => $e->getMessage () );
			$version->save();
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
					
			})
				
				
				
			;
		}
		return $projects;
	}

	public function show($id) {
		$p = Project::find ( $id );
		// $p->graph = json_decode($p->graph);
		$p->documents;
		return $p;
	}

	public function update($id) {
		$project = Project::find ( $id );
		$data = Input::get ();
		if (! empty ( $data ['graph'] )) {
			$graph = json_decode ( $data ['graph'] );
			foreach ( $graph->cells as $node ) {
				if ($node->type != 'fsa.Arrow')
				continue;
				$src = Document::find ( $node->source->id );
				$dst = Document::find ( $node->target->id );
				$src->dests ()->attach ( $dst );
			}
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
