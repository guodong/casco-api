<?php
use Illuminate\Support\Facades\Input;
class ReportCoverController extends BaseController{
    public function index() {
        $cover = ReportCover::where('report_id','=',Input::get('report_id'))->get();
        
    }
}