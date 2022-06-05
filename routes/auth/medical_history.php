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

$api->get('/medicalhistories', [
    'action' => 'VIEW-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@search',
]);


$api->get('/medicalhistory/{id:[0-9]+}', [
    'action' => 'VIEW-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@detail',
]);


$api->get('/medicalhistory/info', [
    'action' => 'VIEW-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@getInfo',
]);

//thong tin lịch sử của user và theo id bệnh nhân
$api->get('/medicalhistory/info/dr-patient/{id:[0-9]+}', [
    'action' => 'VIEW-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@getinfobyPatientId',
]);

//count Patients in Today
$api->get('/medicalhistory/doctor/count-patients-in-today', [
    'action' => 'VIEW-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@CountPatientToday',
]);


$api->get('/medicalhistory/info/listPatients', [
    'action' => 'VIEW-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@getListPatient',
]);


$api->get('/medicalhistory/info/{id:[0-9]+}', [
    'action' => 'VIEW-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@getidPatientHistory',
]);

$api->get('/medicalhistory/info/patient/{id:[0-9]+}', [
    'action' => 'VIEW-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@getidPatientHistoryId',
]);

//follow user patients
$api->get('/medicalhistory/info/patient', [
    'action' => 'VIEW-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@getinfobyPatient',
]);

$api->post('/medicalhistory', [
    'action' => 'CREATE-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@create',
]);

//if bookrecord haven't empty. Doctor will create usersPatient -> bookRecordHealth->MedicalHistory
$api->post('/medicalhistoryv1', [
    'action' => 'CREATE-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@createV1',
]);


//for patient bby user login account
$api->put('/medicalhistory/info/{id:[0-9]+}', [
    'action' => 'UPDATE-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@update',
]);

//create test result
$api->post('/medicalhistory/info/{id:[0-9]+}', [
    'action' => 'UPDATE-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@creatTestResult',
]);
//creatTestResult

$api->delete('/medicalhistory/{id:[0-9]+}', [
    'action' => 'DELETE-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@delete',
]);
$api->delete('/medicalhistory/info/{id:[0-9]+}', [
    'action' => 'DELETE-MEDICAL-HISTORY',
    'uses' => 'MedicalHistoryController@delete',
]);