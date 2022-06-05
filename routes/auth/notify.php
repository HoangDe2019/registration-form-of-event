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

$api->get('/notifies', [
    'action' => 'VIEW-NOTIFY',
    'uses' => 'NotifyController@search',
]);

$api->get('/notifies/{id:[0-9]+}', [
    'action' => 'VIEW-NOTIFY',
    'uses' => 'NotifyController@detail',
]);

$api->post('/notifies', [
    'action' => 'CREATE-NOTIFY',
    'uses' => 'NotifyController@create',
]);

$api->put('/notifies/{id:[0-9]+}', [
    'action' => 'UPDATE-NOTIFY',
    'uses' => 'NotifyController@update',
]);

$api->delete('/notifies/{id:[0-9]+}', [
    'action' => 'DELETE-NOTIFY',
    'uses' => 'NotifyController@delete',
]);