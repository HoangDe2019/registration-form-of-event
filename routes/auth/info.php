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

$api->get('/info', [
    'action' => 'VIEW-CULTURE',
    'uses' => 'InfoController@search',
]);

$api->get('/info/{id:[0-9]+}', [
    'action' => 'VIEW-CULTURE',
    'uses' => 'InfoController@detail',
]);

$api->post('/info', [
    'action' => 'CREATE-CULTURE',
    'uses' => 'InfoController@create',
]);

$api->put('/info/{id:[0-9]+}', [
    'action' => 'UPDATE-CULTURE',
    'uses' => 'InfoController@update',
]);

$api->delete('/info/{id:[0-9]+}', [
    'action' => 'UPDATE-CULTURE',
    'uses' => 'InfoController@delete',
]);