<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$api->post('/news', [
    'action' => 'CREATE-NEWS',
    'uses' => 'NewsController@create',
]);

$api->put('/news/{id:[0-9]+}', [
    'action' => 'CREATE-NEWS',
    'uses' => 'NewsController@update',
]);