<?php

class TreeController extends Controller{

    public function index()
    {    
    	
    	 $rt=Document::whereRaw("fid=0")->get();
    	 $root=array(
            'text'=>'/',
            'leaf'=>false,
            'children'=>$rt
            );
        return $root;
        if(isset($_GET['node'])){
            $docs = Document::where('fid', '=', $_GET['node'])->get();
        }else{
            $docs = Document::whereRaw("project_id = {$_GET['project_id']} and fid = 0")->get();
        }
        $rt = array();
        foreach($docs as $d){
            $lf = ($d->type == 'folder')?false:true;
            $rt[] = array(
                    'name' => $d->name,
                    'leaf' => $lf,
                    'id' => $d->id,
                    'type' => $d->type,
                    'versions' => $d->versions
            );
        }
        $r = array('children'=>$rt);
        return json_encode($r);
    }

    public function poweredit()
    {
        $data =(Input::get('data'));
        $user_id=Input::get('user_id');
       // var_dump($data);
        
        foreach ($data as $key=>$value){
            $pu = ProjectUser::where('project_id', $key)->where('user_id', $user_id)->first();
            if(!count($pu)){
                ProjectUser::create(['project_id'=>"$key", 'user_id'=>"$user_id", 'doc_edit'=>"$value"]);
            }else{
                $pu->doc_edit = $value;
                $pu->save();
            }
        }
    }
    
    public function root()
    {    
    	//思考:如何返回所有的工程的文档结构呢?怎样显示用户已经具有的权限呢?部署机上面的数据库格式是什么样子的呢？
    	if(!Input::get('user_id')){
         $docs = Document::whereRaw("project_id = ? and fid = ?", array($_GET['project_id'], 0))->get();
         $rt = array();
         foreach($docs as $d){
             $lf = ($d->type == 'folder')?false:true;
             $rt[] = array(
                     'name' => $d->name,
                     'leaf' => $lf,
                     'id' => $d->id,
                     'type' => $d->type,
                    'versions' => $d->versions
             );
         }
         $r = array(
            'text'=>'/',
            'name'=>'/',
            'id'=>0,
            'type'=>'folder',
            'leaf'=>false,
            'children'=>$rt
             );
         return  array('children'=>$r);
    	}else{

         	//假设认为文档project_id是可以信任的那么,行吧就写着里面的了
             //checked掉用户已经拥有的id,把用户已经拥有的document_id拼接为数组
            $user_docs=DB::table('project_user')->where('user_id','=',Input::get('user_id'))->select('doc_edit')->get();
            //有可能是个数组
         //var_dump($user_docs);
            $mine_docs=array();
           // Console::info(  ); 
            foreach($user_docs  as $docs){
            //   Log::info(json_encode($docs));
            //   Log::info(json_encode($docs[0]));
            //   Log::info(json_encode($docs["doc_edit"]));
              
            	$mine_docs=array_merge($mine_docs,explode(',',json_encode($docs["doc_edit"])));
         
            }
            //
            function m_trim($v){
            	
            return trim($v,'\'\"\r\n\0 ');//规范化数组
            	
            }
            $mine_docs=array_map("m_trim",$mine_docs);
          //  这里只用显示出用户的project既可以的了
         
         	$project=DB::table('project')->where('id','=',Input::get('project_id'))->select('name','id')->get();
         	$root=array();
         	foreach($project as $pros){
            //遍历每个工程的文档,fid=0是为了查找第一级的节点哦
            Log::info(gettype($pros));
            $docs = Document::whereRaw("project_id = ? and fid = ?", array($pros["id"], 0))->get();    
         	//$docs = Document::whereRaw("project_id = ? and fid = ?", array($pros->id, 0))->get();
         	$rt = array();
            foreach($docs as $d){
            //如果是folder类型,还要拼接相关的chilrens
            $children=array();
            if($d->type == 'folder'){
            
            $folder_docs= DB::table('document')->where('fid', '=', $d->id)->get();
            foreach($folder_docs as $chils){
          
           // var_dump(in_array('d6889236-ad21-11e4-aa9b-cf2d72b432dc',$mine_docs));
          
            $children[]=array(
            'doc_name' => $chils["name"],
            'leaf' =>true ,
            'text'=>$chils["name"],
            'checked'=>in_array($chils["id"],$mine_docs)?true:false,
            'doc_id' => $chils["id"],
            'doc_type' => $chils["type"],
            'type'=>'doc'
            
            
            );
            	
            }//foreach
             $lf = false;
             $rt[] = array(
                     'doc_name' => $d->name,
                     'leaf' => $lf,
                     'text'=>$d->name,
                     'checked'=>in_array($d->id,$mine_docs)?true:false,
                     'doc_id' => $d->id,
                     'doc_type' => $d->type,
                     'children'=>$children,
                     'type'=>'folder'
             );
            
            }else{//if folder else leaf
             $lf = true;
             $rt[] = array(
                     'doc_name' => $d->name,
                     'leaf' => $lf,
                     'text'=>$d->name,
                     'checked'=>in_array($d->id,$mine_docs)?true:false,
                     'doc_id' => $d->id,
                     'doc_type' => $d->type,
                     'type'=>'doc'
                    
             );
            }//else
            }//foreach docs
            
            function  filt($array){
            	
            	return $array['checked'];
            	
            	
            }
           
           $ans=(count(array_filter($rt,'filt'))==sizeof($rt));
            
            $root[]=array(
            'pro_name'=>$pros["name"],
            'text'=>$pros["name"],
            'id'=>$pros["id"],
            'checked'=>$ans,
            'leaf'=>false,
            'type'=>'project',
            'children'=>$rt
            );
         	}//foreach $projects
         	return json_encode($root);
    	}//else  	
    }
    
    public function show($foder_id)
    {
        $docs = Document::where('fid', '=', $foder_id)->get();
        $rt = array();
        foreach($docs as $d){
            $lf = ($d->type == 'folder')?false:true;
            $rt[] = array(
                    'name' => $d->name,
                    'leaf' => $lf,
                    'id' => $d->id,
                    'type' => $d->type,
                'versions' => $d->versions
            );
        }
        $r = array('children'=>$rt);
        return json_encode($r);
    }
    public function store(){
        //treenode的修改方式
    	$document = new Document(Input::get());
        $document->save();
        return $document;	
    }
    public function treemod()
    {
        $src = Document::find($_GET['src']);
        $dst = Document::find($_GET['dst']);
        if($dst->type == 'folder'){
            $src->fid = $dst->id;
            $src->save();
        }else{
            $src->fid = $dst->fid;
            $src->save();
        }
    }
}
