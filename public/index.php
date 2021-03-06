<?php
//error_reporting(E_ALL & ~E_DEPRECATED);
define('PATH_BASE', realpath(__DIR__.'/../'));
define('COL_PREFIX','(P) // (C)');
define('MID_COMPOSE',' // ');
$black=array('description','test case description','test description');
$origin = array_key_exists('HTTP_ORIGIN', $_SERVER)?$_SERVER['HTTP_ORIGIN']:'*';
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: '.$origin);
//header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET,PUT,DELETE,POST,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type,X-Requested-With');

 ini_set('max_execution_time','0'); 
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.'/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let's turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight these users.
|
*/

$app = require_once __DIR__.'/../bootstrap/start.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can simply call the run method,
| which will execute the request and send the response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have whipped up for them.
|
*/

$app->run();
