<?php
use Illuminate\Support\Facades\Input;

class ProjectUserController extends BaseController
{
    
    // 项目创建
    public function index()
    {
    	//根据user_id来获取相应的记录.
    	if(Input::get('user_id')){
    	//$pro_user=ProjectUser::where('user_id','=',Input::get('user_id'))->get();
    	  $user_docs=DB::table('project_user')->where('user_id',Input::get('user_id'))->join('project','project_user.project_id','=','project.id')->select('project_user.user_id','project.name as project_name','project_user.doc_edit as documents_id')->get();
    	//	echo $user_docs->toSql();
    		//exit();
    	   return $user_docs;
    	}
    	
    	return  $this->outputError('please input user_id!');
    	
    	
    		
    	
    }
    
    
     public function show($id)
    {
        $document = Document::find($id);
        $document->versions;
        return $document;
    }

    public function store()
    {
        $document = new Document(Input::get());
        $document->save();
        return $document;
    }

    public function update()
    {
    
    	
    	
    	
    	
    	
    	
    }
    
    
    
}