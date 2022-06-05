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

$api->get('/prescriptions', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@search',
]);

//danh sach toa thuoc duoc tao boi bac si
$api->get('/prescriptions/userDrIdCreated', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@searchDrCreated',
]);

$api->get('/prescription/userDrId/getListPreId', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@getListPreId',
]);

$api->get('/prescriptions/userDrHaveForPatient', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@searchDoctorPre',
]);

//Toan bo du lieu cua benh nhan va bac si c
$api->get('/prescriptions/userDrIdPatient/{id:[0-9]+}', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@searchDoctorsIdPatient',
]);
//Danh sach du lieu cua id benh nhan
$api->get('/prescriptions/userDrId/{id:[0-9]+}', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@searchDoctors',
]);
//

$api->get('/prescriptions/userPt', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@searchPatients',
]);


$api->get('/prescription/@/detail/{id:[0-9]+}', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@detail',
]);
$api->get('/prescription/@/detail/exportPDF/{id:[0-9]+}', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@exportPrePDFforPatients',
]);

$api->get('/prescription/{id:[0-9]+}', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@searchPatients',
]);

$api->post('/prescription', [
    'action' => 'CREATE-PRESCRIPTION',
    'uses' => 'PrescriptionController@create',
]);


$api->put('/prescription/{id:[0-9]+}/detail', [
    'action' => 'UPDATE-PRESCRIPTION',
    'uses' => 'PrescriptionController@addMedicines'
]);



//
//$api->put('/analysis/{id:[0-9]+}', [
//    'action' => 'UPDATE-analysis',
//    'uses' => 'PrescriptionController@update',
//]);

$api->delete('/prescription/{id:[0-9]+}', [
    'action' => 'DELETE-PRESCRIPTION',
    'uses' => 'PrescriptionController@delete',
]);

