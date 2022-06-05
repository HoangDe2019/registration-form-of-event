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

$api->get('/weeks', [
    'action' => 'VIEW-WEEKS',
    'uses' => 'WeekController@search',
]);

    $api->get('/week/{id:[0-9]+}', [
    'action' => 'VIEW-WEEKS',
    'uses' => 'WeekController@detailWeek',
]);

$api->post('/week', [
    'action' => 'CREATE-WEEKS',
    'uses' => 'WeekController@create',
]);

$api->put('/week/{id:[0-9]+}', [
    'action' => 'UPDATE-WEEKS',
    'uses' => 'WeekController@update',
]);

$api->delete('/week/{id:[0-9]+}', [
    'action' => 'DELETE-WEEKS',
    'uses' => 'WeekController@delete',
]);
