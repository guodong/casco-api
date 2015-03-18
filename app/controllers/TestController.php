<?php

use Illuminate\Support\Facades\Input;
class TestController extends Controller{

    public function index()
    {
        $tc = Tc::find("399b97e7-776f-49f3-b4fe-ffc218f0ff55");
        
    }


}
