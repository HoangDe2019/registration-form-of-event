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

$api->get('/healthrecordbooks', [
    'action' => 'VIEW-HEALTH-RECORD-BOOKS',
    'uses' => 'HealthBookRecordController@searchPatient',
]);

$api->get('/healthrecordbook/{id:[0-9]+}', [
    'action' => 'VIEW-HEALTH-RECORD-BOOKS',
    'uses' => 'HealthBookRecordController@detaildoctorsbyPatient',
]);
/*
$api->post('/healthrecordbook', [
    'action' => 'CREATE-WEEKS',
    'uses' => 'WeekController@updatePatients',
]);
*/
$api->put('/healthrecordbook/{id:[0-9]+}', [
    'action' => 'UPDATE-HEALTH-RECORD-BOOKS',
    'uses' => 'HealthBookRecordController@updatePatients',
]);
/*
$api->delete('/week/{id:[0-9]+}', [
    'action' => 'DELETE-WEEKS',
    'uses' => 'WeekController@delete',
]);
*/
