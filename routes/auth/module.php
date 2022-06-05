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

$api->get('/module', [
    'action' => 'VIEW-MODULE',
    'uses' => 'ModuleController@search',
]);

$api->get('/module/{id:[0-9]+}', [
    'action' => 'VIEW-MODULE',
    'uses' => 'ModuleController@detail',
]);

$api->get('/module/{id:[0-9]+}/users', [
    'action' => 'VIEW-MODULE',
    'uses' => 'ModuleController@userDetail',
]);

$api->post('/module', [
    'action' => 'CREATE-MODULE',
    'uses' => 'ModuleController@create',
]);

$api->put('/module/{id:[0-9]+}', [
    'action' => 'UPDATE-MODULE',
    'uses' => 'ModuleController@update',
]);


$api->delete('/module/{id:[0-9]+}', [
    'action' => 'DELETE-MODULE',
    'uses' => 'ModuleController@delete',
]);