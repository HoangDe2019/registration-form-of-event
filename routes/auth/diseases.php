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

$api->get('/diseases', [
    'action' => 'VIEW-DISEASES',
    'uses' => 'DiseasesController@search',
]);

$api->get('/disease/{id:[0-9]+}', [
    'action' => 'VIEW-DISEASES',
    'uses' => 'DiseasesController@detail',
]);

$api->post('/disease', [
    'action' => 'CREATE-DISEASES',
    'uses' => 'DiseasesController@create',
]);

$api->put('/disease/{id:[0-9]+}', [
    'action' => 'UPDATE-DISEASES',
    'uses' => 'DiseasesController@update',
]);

$api->delete('/disease/{id:[0-9]+}', [
    'action' => 'DELETE-DISEASES',
    'uses' => 'DiseasesController@delete',
]);