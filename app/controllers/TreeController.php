<?php

class TreeController extends Controller{

    public function index()
    {
        return Document::whereRaw("fid = 0")->get();
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
        $data = Input::get('data');
        foreach ($data as $v){
            $pu = ProjectUser::where('project_id', $v['project_id'])->where('user_id', $v['user_id'])->get();
            if(!count($pu)){
                ProjectUser::create(['project_id'=>$v['project_id'], 'user_id'=>$v['user_id'], 'doc_edit'=>implode(',', $v['doc_edit'])]);
            }else{
                $pu->doc_edit = implode(',', $v['doc_edit']);
                $pu->save();
            }
        }
    }
    
    public function root()
    {    
    	//思考:如何返回所有的工程的文档结构呢?怎样显示用户已经具有的权限呢?部署机上面的数据库格式是什么样子的呢？
    	if(Input::get('project_id')){
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
         $r = array('children'=>$rt);
         return json_encode($r);
    	}else{
         	//假设认为文档project_id是可以信任的那么,行吧就写着里面的了
             //checked掉用户已经拥有的id,把用户已经拥有的document_id拼接为数组
            $user_docs=DB::table('project_user')->where('user_id','=',Input::get('user_id'))->select('doc_edit')->get();
            //有可能是个数组
      //      var_dump($user_docs);
            $mine_docs=array();
            foreach($user_docs  as $docs){
            	
            	$mine_docs=array_merge($mine_docs,explode(',',json_encode($docs->doc_edit)));
            		
            }
            //
            function m_trim($v){
            	
            return trim($v,'\'\"\r\n\0 ');//规范化数组
            	
            }
            $mine_docs=array_map("m_trim",$mine_docs);
          //  var_dump($mine_docs);
   
         	$project=DB::table('project')->select('name','id')->get();
         	$root=array();
         	foreach($project as $pros){
            //遍历每个工程的文档,fid=0是为了查找第一级的节点哦
        
         	$docs = Document::whereRaw("project_id = ? and fid = ?", array($pros->id, 0))->get();
         	$rt = array();
            foreach($docs as $d){
            //如果是folder类型,还要拼接相关的chilrens
            $children=array();
            if($d->type == 'folder'){
            
            $folder_docs= DB::table('document')->where('fid', '=', $d->id)->get();
            foreach($folder_docs as $chils){
          
           // var_dump(in_array('d6889236-ad21-11e4-aa9b-cf2d72b432dc',$mine_docs));
          
            $children[]=array(
            'doc_name' => $chils->name,
            'leaf' =>true ,
            'text'=>$chils->name,
            'checked'=>in_array($chils->id,$mine_docs)?true:false,
            'doc_id' => $chils->id,
            'doc_type' => $chils->type,
            
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
                     'children'=>$children
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
                    
             );
            }//else
            }//foreach docs
            
            $root[]=array(
            'pro_name'=>$pros->name,
            'text'=>$pros->name,
            'id'=>$pros->id,
          //  'checked'=>true,
            'leaf'=>false,
            'children'=>$rt
            );
         	
         		
         		
         		
         	}//foreach $projects
         	return json_encode($root);
         	
         }
        
         	
         	
         	
       
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
