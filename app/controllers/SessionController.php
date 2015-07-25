<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class SessionController extends BaseController
{

    public function index()
    {
        if (Session::has('uid')){
            return $this->output(User::find(Session::get('uid')));
        }else{
            return $this->outputError('not login');
        }
    }

    public function show($id)
    {
        $document = Document::find($id);
        $document->versions;
        return $document;
    }

    public function store()
    {
        Session::put('uid', 1);
        return 'ok';
    }

}
