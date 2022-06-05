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

$api->get('/diagnosed', [
    'action' => 'VIEW-DISEASES-DIAGNOSED',
    'uses' => 'DiseasesDiagnosedController@search',
]);
$api->get('/diagnosed/{id:[0-9]+}', [
    'action' => 'VIEW-DISEASES-DIAGNOSED',
    'uses' => 'DiseasesDiagnosedController@detail',
]);

$api->put('/diagnosed/{id:[0-9]+}/disease', [
    'action' => 'UPDATE-DISEASES-DIAGNOSED',
    'uses' => 'DiseasesDiagnosedController@addDiseases',
]);

/*
$api->delete('/diagnosed/{id:[0-9]+}', [
    'action' => 'DELETE-DISEASES-DIAGNOSED',
    'uses' => 'DiseasesDiagnosedController@delete',
]);

*/