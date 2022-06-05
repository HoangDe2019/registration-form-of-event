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

//xem danh sach bac si
$api->get('/userpatients', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@searchbyPatient',
]);

$api->get('/userpatients/search', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@searchDoctors',
]);

// chi tiet thong tin bac sĩ
$api->get('/userpatients/{id:[0-9]+}', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@detaildoctorsbyPatient',
]);

$api->get('/fillter', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@fillter',
]);

//Export pdf Pre
$api->get('/prescription/@/detail/exportPDF/{id:[0-9]+}', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'PrescriptionController@exportPrePDFforPatients',
]);

//Đăng ký thông tin bệnh nhân chưa có khám lần nào
$api->post('/userpatients', [
    'action' => 'CREATE-USER',
    'uses' => 'HealthBookRecordController@createPatients',
]);

$api->post('/userpatients2021', [
    'action' => 'CREATE-USER',
    'uses' => 'UserController@updatePatient',
]);
//khôi phục mật khẩu
$api->put('/userpatients', [
    'action' => 'UPDATE-USER',
    'uses' => 'UserController@resetPassword',
]);

$api->put('/userpatients2020', [
    'action' => 'UPDATE-USER',
    'uses' => 'UserController@verifyCode',
]);
//
$api->post('/userpatients/email/HaveAcconutUpdate', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@sendEmailBeforeUpdateAccount',
]);

//updatePatient

//city
$api->get('/api/city', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'UserController@cityDfs',
]);

$api->get('/api/city/district/ward', [
    'action' => 'VIEW-PRESCRIPTION',
    'uses' => 'UserController@jsonCity_Dictrict_Ward',
]);

$api->get('/users/export', [
    'action' => 'VIEW-USER-EXPORT',
    'uses' => 'UserController@exportUser',
]);



$api->get('/week/user/{id:[0-9]+}/medical-schedules', [
    'action' => 'VIEW-MEDICAL_SCHEDULES',
    'uses' => 'UserController@userofSchdule',
]);

$api->post('/test', [
    'action' => 'VIEW-MEDICAL_SCHEDULES',
    'uses' => 'WeekController@test',
]);