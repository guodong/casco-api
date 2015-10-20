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
		foreach ( $obj as $key => $value ) {
			if (gettype ( $value ) == 'array' || gettype ( $value ) == 'object') {
				$ret [$key] = objtoarr ( $value );
			} else {
				$ret [$key] = $value;
			}
		}
		return $ret;
	}
	public function docfile() {
		
		if (Input::get ( 'isNew' ) == 1)
			$version = Version::create ( array ('name' => Input::get ( 'name' ), 'document_id' => Input::get ( 'document_id' ) ) );
		else
			$version = Version::find ( Input::get ( 'version_id' ) );
		set_time_limit ( 0 );
		$name = uniqid () . '.doc';
		//echo $name;
		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], public_path () . '/files/' . $name );
		$version->filename = $name;
		//$version->save ();
		
		$url_path = public_path () . '/files/' . $name;
		//存贮列名到version里面的headers
		$column = Input::get ("column");
		$version->headers=strtolower($column);//此时column还没有过滤
		//$version->save();
		
		
		
		try {
			$soap = new SoapClient ( "http://localhost:2614/WebService2.asmx?WSDL" );
			//$result2 = $soap->test( array ('url'=>'123' ) );
			
			$type = Input::get ( "type" );
			$doc_url = 'http://127.0.0.1/casco-api/public/files/' . $name;
			
			$result2 = $soap->resolve ( array ('column' => $column, 'type' => $type, 'doc_url' => $doc_url ) );
			// echo  "{success:true,info:'kaka!'}";
		

		//   return (array("success"=>true, "msg"=>$this->objtoarr($result2)));
		

		} catch ( SoapFault $e ) {
			return array ('success' => false, 'msg' => $e->getMessage () );
		} catch ( Exception $e ) {
			return array ('success' => false, 'msg' => $e->getMessage () );
		}
		
		$add = 0;
		$modify = 0;
		$wait_save = array ();
		$data = $this->objtoarr ( $result2 ); //返回tc文档的具体信息
		$resolveResult = json_decode ( $data ['resolveResult'] );
		if (! $data) {
			return "读取文档失败，请检查字段或配置";
		}
		// var_dump(($resolveResult));
		

		if (Input::get ( 'type' ) == 'tc') {
			foreach ( ( array ) $resolveResult as $value ) {
				
				$num = Tc::where ( "tag", "=", $value->tag )->where ( "version_id", "=", Input::get ( 'version_id' ) )->first ();
				
				if ($num) {
					$num->column = "";
					foreach ( $value as $key => $item ) {
						
						if ($key != 'tag' && $key != 'test steps') {
							$tc->column .= '"'.strtolower($key) . '":"' . $item . '",';
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
					$tc->version_id = Input::get ( "version_id" );
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
			
			foreach ( ( array ) $resolveResult as $value ) {
				
				$rs = Rs::where ( "tag", "=", $value->tag )->where ( "version_id", "=", Input::get ( 'version_id' ) )->first ();
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
					$rs->version_id = Input::get ( "version_id" );
					$rs->save ();
					$add++;
					 
				
				}//else
			} //foreach
		} //if rs 
		
		         $version->result="增添".$add."条,修改".$modify."条!";
		         $version->save();
		 return  array('success'=>true,"msg"=>"增添".$add."条,修改".$modify."条!");
	/*          
            $d = json_decode($data);
            if (! $d) {
                return array('success'=>false, 'msg'=>'parse error');
            }
            foreach ($d as $v) {
                if (empty($v))
                    continue;
                $tc = new Tc();
                //$tc->document_id = $_POST['document_id'];
                $tc->tag = $v->tag;
                $tc->description = $v->description;
                $tc->pre_condition = $v->pre_condition;
                $tc->version_id = $version->id;
                $tc->source_json = json_encode($v->source);
                $tc->save();
                foreach ($v->steps as $step){
                    $step = new TcStep((array)$step);
                    $tc->steps()->save($step);
                }
//                 foreach ($v->source as $source){
//                     $s = Rs::where('tag', '=', $source)->orderBy('created_at', 'desc')->first();
//                     if($s){
//                         $tc->sources()->attach($s->id, array('tag'=>$source));
//                     }
//                 }
            }
            $rt = Tc::where('version_id', '=', $version->id)->get();
           
        }
        
        // RS 
        $modify=0;$delete=0;$add=0;$flag_add=true;
        $url = 'http://192.100.212.31:8080/files/' . $name;
        $u = 'http://192.100.212.33/WebService1.asmx/InputWord?url=' . $url.'&filename='.$name;
        $data = file_get_contents($u);
        $d = json_decode($data);
        if (! $d) {
            return 1;
        }
        foreach ($d as $v) {
            if (empty($v))
                continue;
            $rs = new Rs();
            
            //$rs->document_id = $_POST['document_id'];
            $rs->tag = $v->title;
            //这里来进行一下遍历吧
            $document_id=$_POST['document_id'];
            foreach($document->rss as $rss){
            if($rss->tag==$v->title){
              //接下来查看是否所有的内容都已经修改
             $flag_add=false;
             $flag=($rss->allocation==$v->Allocation&&$rss->category==$$v->Category&&$rss->implement==$v->Implement
             &&$rss->priority==$v->Priority&&$rss->contribution==$v->Contribution&&$rss->description==$v->description
             &&$rss->source_json==json_encode($v->Source)&&$rs->version_id = $version->id);
             if(!$flag)$modify++;
             break;//得到结果，break;
            }else{
            	
            	continue;
            }
            
            $flag_add?$add++:'';
            
            }//foreach
            	
          
            $rs->allocation = $v->Allocation;
            $rs->category = $v->Category;
            $rs->implement = $v->Implement;
            $rs->priority = $v->Priority;
            $rs->contribution = $v->Contribution;
            $rs->description = $v->description;
            $rs->source_json = json_encode($v->Source);
            $rs->version_id = $version->id;
            $rs->save();
//             $rs->sources()->detach();
//             foreach ($v->Source as $source){
//                 $s = Rs::where('tag', '=', $source)->orderBy('created_at', 'desc')->first();
//                 if($s){
//                     $rs->sources()->attach($s->id);
//                 }
//             }
        }
        $rt = Rs::where('version_id', '=', $version->id)->get();
        $ans='{"msg":[{"add":$add,"modify":$modify,"delete":$delete}]}';
        return  $ans;
       
            */
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
