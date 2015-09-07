<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('/test', 'TestController@index');
Route::get('tc/export', 'TcController@export');
Route::resource('project', 'ProjectController');

Route::get('tc/maxtag', 'TcController@maxtag');
Route::resource('rs', 'RsController');
Route::resource('tc', 'TcController');
Route::resource('document', 'DocumentController');
Route::resource('document.rs', 'DocumentRsController');
Route::get('treevat/root', 'TreeVatController@root');
Route::resource('treevat', 'TreeVatController');
Route::get('tree/root', 'TreeController@root');
Route::resource('tree', 'TreeController');
Route::get('treeitem/root', 'TreeController@root');
Route::resource('treeitem', 'TreeItemController');
Route::get('treemod', 'TreeController@treemod');
Route::post('docfile', 'ProjectController@docfile');
Route::resource('vatstr', 'VatstrController');
Route::resource('user', 'UserController');
Route::resource('testmethod', 'TestmethodController');
Route::get('dump', 'DumpController@dump');
Route::resource('build', 'BuildController');
//Route::get('version', 'DocumentController@version');
Route::get('stat/count', 'StatController@count');
Route::post('setresult', 'TcController@setresult');
Route::get('fix', 'FixController@index');
Route::resource('version', 'VersionController');

Route::get('testjob/export', 'TestjobController@export');
Route::resource('testjob', 'TestjobController');
Route::get('session', 'UserController@session');
Route::resource('result', 'ResultController');
Route::post('login', 'UserController@login');
Route::get('logout','UserController@logout');