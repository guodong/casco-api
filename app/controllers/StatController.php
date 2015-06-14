<?php
use Illuminate\Support\Facades\Input;

class StatController extends Controller
{
    private function getNum($project, $timestamp)
    {
        $num = 0;
        foreach ($project->documents as $doc){
            if ($doc->type == 'rs'){
                $num += Rs::where('document_id', '=', $doc->id)->where('created_at', '<=', date('Y-m-d H:i:s', $timestamp))->count();
            }elseif ($doc->type == 'tc'){
                $num += Tc::where('document_id', '=', $doc->id)->where('created_at', '<=', date('Y-m-d H:i:s', $timestamp))->count();
            }
        }
        return $num;
    }
    
    public function count()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $step = Input::get('step');
        if (!$step){
            $step = 3600*24;
        }
        $projects = Project::all();
        foreach ($projects as $p){
            $time = (int)$from;
            $stat = array();
            while ($time <= $to){
                $data = new stdClass();
                $data->time = $time;
                $data->number = $this->getNum($p, $time);
                array_push($stat, $data);
                $time += $step;
            }
            $p->stat = $stat;
        }
        return $projects;
    }

    public function index()
    {
        if (! empty($_GET['project_id'])) {
            $d = Document::where('project_id', '=', $_GET['project_id']);
            if (Input::get('type') == 'tc') {
                $d = $d->where('type', '=', 'tc');
            } else 
                if (Input::get('type') == 'rs') {
                    $d = $d->where('type', '=', 'rs');
                } else {
                    $d = $d->where('type', '<>', 'folder');
                }
            $data = $d->get();
            foreach ($data as $v) {
                if ($v->type == 'rs') {
                    $v->count = count($v->rss);
                    $v->num_passed = 0;
                    $v->num_failed = 0;
                    $v->num_untested = 0;
                    foreach ($v->rss as $rs) {
                        if ($this->calresult($rs) == 0)
                            $v->num_untested ++;
                        elseif ($this->calresult($rs) == 1) {
                            $v->num_passed ++;
                        } else {
                            $v->num_failed ++;
                        }
                    }
                } else {
                    $v->count = count($v->tcs);
                    $v->num_passed = 0;
                    $v->num_failed = 0;
                    $v->num_untested = 0;
                    foreach ($v->tcs as $tc) {
                        if ($tc->result == 0)
                            $v->num_untested ++;
                        elseif ($tc->result == 1) {
                            $v->num_passed ++;
                        } else {
                            $v->num_failed ++;
                        }
                    }
                }
            }
            return Response::json($data);
        }
        return Document::all();
    }

    public function show($id)
    {
        return Document::find($id);
    }

    public function store()
    {
        $document = new Document(Input::get());
        $document->save();
        return $document;
    }

    public function update($id)
    {}

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
}
