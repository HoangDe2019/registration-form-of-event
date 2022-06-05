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

$api->get('/analysises', [
    'action' => 'VIEW-ANALYSISES',
    'uses' => 'AnalysisController@search',
]);

$api->get('/analysis/{id:[0-9]+}', [
    'action' => 'VIEW-ANALYSISES',
    'uses' => 'AnalysisController@detailAnalysis',
]);

$api->post('/analysis', [
    'action' => 'CREATE-ANALYSISES',
    'uses' => 'AnalysisController@create',
]);

$api->put('/analysis/{id:[0-9]+}', [
    'action' => 'UPDATE-ANALYSISES',
    'uses' => 'AnalysisController@update',
]);

$api->delete('/analysis/{id:[0-9]+}', [
    'action' => 'DELETE-ANALYSISES',
    'uses' => 'AnalysisController@delete',
]);
