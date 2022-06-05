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

$api->get('/medical-schedules', [
    'action' => 'VIEW-MEDICAL_SCHEDULES',
    'uses' => 'MedicalScheduleController@search',
]);
$api->get('/week/{id:[0-9]+}/medical-schedules', [
    'action' => 'VIEW-MEDICAL_SCHEDULES',
    'uses' => 'WeekController@userDoctor_week_scheuled',
]);



$api->get('/weeks/medical-schedules', [
    'action' => 'VIEW-MEDICAL_SCHEDULES',
    'uses' => 'WeekController@search',
]);
$api->get('/medical-schedule/{id:[0-9]+}', [
    'action' => 'VIEW-MEDICAL_SCHEDULES',
    'uses' => 'MedicalScheduleController@detailMedicalSchedule',
]);

$api->post('/medical-schedules', [
    'action' => 'CREATE-MEDICAL_SCHEDULES',
    'uses' => 'MedicalScheduleController@create',
]);
//
$api->put('/medical-schedule/{id:[0-9]+}', [
    'action' => 'UPDATE-MEDICAL_SCHEDULES',
    'uses' => 'MedicalScheduleController@update',
]);
//
$api->delete('/medical-schedule/{id:[0-9]+}', [
    'action' => 'DELETE-MEDICAL_SCHEDULES',
    'uses' => 'MedicalScheduleController@delete',
]);