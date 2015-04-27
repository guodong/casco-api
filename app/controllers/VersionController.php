<?php

use Illuminate\Support\Facades\Input;
class VersionController extends BaseController {

	public function index()
	{
	    $versions = Version::where('document_id', '=', Input::get('document_id'))->orderBy('created_at', 'desc')->get();
		return $versions;
	}
	
}
