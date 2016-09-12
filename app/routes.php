<?php

Route::get('/test', 'TestController@index');
Route::get('tc/export', 'TcController@export');
Route::get('tc/matrix','TcController@matirx');
Route::resource('project', 'ProjectController');
Route::get('tc/tc_steps', 'TcController@tc_steps');
Route::get('tc/maxtag', 'TcController@maxtag');
Route::post('rs/multivats','RsController@multivats');
Route::resource('rs', 'RsController');
Route::resource('tc', 'TcController');
Route::resource('document', 'DocumentController');
Route::resource('document.rs', 'DocumentRsController');
Route::get('treevat/root', 'TreeVatController@root');
Route::resource('treevat', 'TreeVatController');
Route::post('tree/poweredit', 'TreeController@poweredit');
Route::get('tree/root', 'TreeController@root');
Route::resource('tree', 'TreeController');
Route::get('treeitem/root', 'TreeController@root');//mine
Route::resource('treeitem', 'TreeItemController');
Route::get('treemod', 'TreeController@treemod');
Route::get('docfile_pre', 'ProjectController@docfile_pre');
Route::post('docfile', 'ProjectController@docfile');
Route::resource('vatstr', 'VatstrController');
Route::resource('user', 'UserController');
Route::resource('role','RoleController');
Route::resource('testmethod', 'TestmethodController');
Route::get('dump', 'DumpController@dump');
Route::get('dump_tag','DumpController@dump_tag');
Route::resource('build', 'BuildController');  
Route::get('stat/count', 'StatController@count');
Route::post('setresult', 'TcController@setresult');
Route::get('fix', 'FixController@index');
Route::get('version/export', 'VersionController@export');
Route::resource('version', 'VersionController');
Route::get('projectuser','ProjectUserController@index');
Route::get('childmatrix/export', 'ChildMatrixController@export');
Route::get('parentmatrix/export', 'ParentMatrixController@export');
Route::post('parentmatrix/updateall', 'ParentMatrixController@updateall');
Route::resource('parentmatrix', 'ParentMatrixController');
Route::resource('childmatrix', 'ChildMatrixController');
Route::get('reportcover/export','ReportCoverController@export');
Route::resource('reportcover','ReportCoverController');
Route::post('reportvats','ReportCoverController@post');
Route::resource('reportcovers','ReportCoversController');

//Vat Module
Route::get('vat/assigned','VatBuildController@assigned_export');
Route::get('vat/assign','VatBuildController@assign_export');
Route::get('vat/export_all','VatBuildController@all_export');
Route::resource('vat','VatBuildController');
Route::post('vat/export','VatBuildController@export');
Route::get('vat/relations','VatBuildController@show');
    
Route::group(array('prefix' => 'testjob'), function()
{
Route::get('export','TestjobController@export');
Route::get('export_pro','TestjobController@export_pro');
Route::get('get_tmp','TestjobController@get_tmp');
Route::post('import_tmp','TestjobController@import_tmp');
});
Route::resource('testjob', 'TestjobController');
Route::resource('testjobtmp', 'TestjobTmpController');
Route::group(array('prefix' => 'verification'), function()
{
Route::get('summary','VerificationController@summary');
Route::get('summary_export','VerificationController@summary_export');
Route::get('export','VerificationController@export');
Route::get('export_all_sheets', 'VerificationController@export_all_sheets');
}
);
//这个要放出来不然找不到的
Route::resource('verification', 'VerificationController');
Route::group(array('prefix' => 'center'), function()
{
Route::get('export_result','ReportController@export_result');
Route::get('result','ReportController@get_result');
Route::get('results','ReportController@get_results');
Route::post('','ReportController@store');
Route::get('export_verify','ReportVerifyController@export_verify');
Route::resource('verify','ReportVerifyController');
Route::get('export_all_sheets', 'ReportController@export_all_sheets');
}
);
Route::resource('center', 'ReportController');
Route::get('session', 'UserController@session');
Route::post('result/updateall', 'ResultController@updateall');
Route::resource('result', 'ResultController');
Route::post('login', 'UserController@login');
Route::get('logout','UserController@logout');