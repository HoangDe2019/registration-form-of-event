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

$api->get('/medicines', [
    'action' => 'VIEW-MEDICINE',
    'uses' => 'MedicineController@search',
]);

$api->get('/medicine/{id:[0-9]+}', [
    'action' => 'VIEW-MEDICINE',
    'uses' => 'MedicineController@detail',
]);

$api->post('/medicine', [
    'action' => 'CREATE-MEDICINE',
    'uses' => 'MedicineController@create',
]);

$api->put('/medicine/{id:[0-9]+}', [
    'action' => 'UPDATE-MEDICINE',
    'uses' => 'MedicineController@update',
]);

$api->delete('/medicine/{id:[0-9]+}', [
    'action' => 'DELETE-MEDICINE',
    'uses' => 'MedicineController@delete',
]);