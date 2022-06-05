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

$api->get('/supports', [
    'action' => 'VIEW-SUPPORT',
    'uses' => 'SupportController@search',
]);

$api->get('/supports/{id:[0-9]+}', [
    'action' => 'VIEW-SUPPORT',
    'uses' => 'SupportController@detail',
]);

$api->post('/supports', [
    'action' => 'CREATE-SUPPORT',
    'uses' => 'SupportController@create',
]);

$api->put('/supports/{id:[0-9]+}', [
    'action' => 'UPDATE-SUPPORT',
    'uses' => 'SupportController@update',
]);

$api->delete('/supports/{id:[0-9]+}', [
    'action' => 'DELETE-SUPPORT',
    'uses' => 'SupportController@delete',
]);