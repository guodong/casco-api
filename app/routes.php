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
Route::resource('version', 'VersionController');
Route::resource('vatstr', 'VatstrController');
Route::resource('user', 'UserController');
Route::get('treemod', 'TreeController@treemod');
Route::get('treeitem/root', 'TreeController@root');
Route::resource('treeitem', 'TreeItemController');
Route::get('tree/root', 'TreeController@root');
Route::resource('tree', 'TreeController');
Route::resource('testmethod', 'TestmethodController');
Route::get('/test', 'TestController@index');
Route::get('tc/export', 'TcController@export');
Route::resource('tc', 'TcController');
Route::get('testjob/rsversion', 'TestjobController@rsversion');
Route::get('testjob/export', 'TestjobController@export');
Route::resource('testjob', 'TestjobController');
Route::get('stat/count', 'StatController@count');
Route::post('setresult', 'TcController@setresult');
Route::get('session', 'UserController@session');
Route::resource('rs', 'RsController');
Route::put('result/updateall', 'ResultController@updateall');
Route::resource('result', 'ResultController');
Route::resource('project', 'ProjectController');
Route::post('login', 'UserController@login');
Route::get('fix', 'FixController@index');
Route::get('dump', 'DumpController@dump');
Route::resource('document.rs', 'DocumentRsController');
Route::resource('document', 'DocumentController');
Route::post('docfile', 'ProjectController@docfile');
Route::resource('build', 'BuildController');


