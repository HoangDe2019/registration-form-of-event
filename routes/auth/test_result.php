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

$api->get('/test-results', [
    'action' => 'VIEW-TEST-RESULTS',
    'uses' => 'TestResultController@search',
]);
$api->get('/test-result/{id:[0-9]+}', [
    'action' => 'VIEW-TEST-RESULTS',
    'uses' => 'TestResultController@detail',
]);

$api->put('/test-result/{id:[0-9]+}/analysis', [
    'action' => 'UPDATE-TEST-RESULTS',
    'uses' => 'TestResultController@update',
]);



//$api->delete('/diagnosed/{id:[0-9]+}', [
//    'action' => 'DELETE-DISEASES-DIAGNOSED',
//    'uses' => 'DiseasesDiagnosedController@delete',
//]);