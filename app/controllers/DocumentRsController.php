<?php

class DocumentRsController extends Controller{

	public function index($doc_id)
	{
		$doc = Document::find($doc_id);
		return $doc->rss;
	}

}
