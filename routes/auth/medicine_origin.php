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

$api->get('/medicine_origins', [
    'action' => 'VIEW-MEDICINE-ORIGIN',
    'uses' => 'MedicineOriginController@search',
]);

$api->get('/medicine_origin/{id:[0-9]+}', [
    'action' => 'VIEW-MEDICINE-ORIGIN',
    'uses' => 'MedicineOriginController@detailMedicineOrigin',
]);

$api->post('/medicine_origin', [
    'action' => 'CREATE-MEDICINE-ORIGIN',
    'uses' => 'MedicineOriginController@create',
]);

$api->put('/medicine_origin/{id:[0-9]+}', [
    'action' => 'UPDATE-MEDICINE-ORIGIN',
    'uses' => 'MedicineOriginController@update',
]);

$api->delete('/medicine_origin/{id:[0-9]+}', [
    'action' => 'DELETE-MEDICINE-ORIGIN',
    'uses' => 'MedicineOriginController@delete',
]);
