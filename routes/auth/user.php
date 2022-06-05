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

$api->get('/users', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@search',
]);

$api->get('/users/{id:[0-9]+}', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@view',
]);

$api->get('/users/patient/{id:[0-9]+}', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@detailPatientbyDoctors',
]);

$api->get('/users/search', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@searchDoctors',
]);


$api->get('/users/export', [
    'action' => 'VIEW-USER-EXPORT',
    'uses' => 'UserController@exportUser',
]);


$api->put('/users/change-password', [
    'action' => 'UPDATE-USER-PASSWORD',
    'uses' => 'UserController@changePassword',
]);


$api->get('/users/info', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@getInfo',
]);


$api->post('/users', [
    'action' => 'UPDATE-USER',
    'uses' => 'UserController@create',
]);


$api->put('/users/{id:[0-9]+}', [
    'action' => 'UPDATE-USER',
    'uses' => 'UserController@update',
]);

$api->put('/users/info', [
    'action' => 'UPDATE-USER',
    'uses' => 'UserController@updateprofile',
]);


$api->put('/users/Dotorinfo', [
    'action' => 'UPDATE-USER',
    'uses' => 'UserController@updateprofileDoctor',
]);

$api->delete('/users/{id:[0-9]+}', [
    'action' => 'DELETE-USER',
    'uses' => 'UserController@delete',
]);

$api->put('/users/{id:[0-9]+}/active', [
    'action' => 'UPDATE-USER',
    'uses' => 'UserController@active',
]);
//them user moi cho nhan vien
$api->post('/users/doctor/role', [
    'action' => 'CREATE-USER-DOCTOR',
    'uses' => 'UserController@createNewDoctor',
]);

$api->put('/users/doctor/{id:[0-9]+}/role-doctor', [
    'action' => 'UPDATE-USER-DOCTOR',
    'uses' => 'UserController@updateUserDoctors',
]);

$api->delete('/users/doctor/{id:[0-9]+}/role-doctor-remove', [
    'action' => 'DELETE-USER-DOCTOR',
    'uses' => 'UserController@delete_userDoctors',
]);

$api->get('/users-doctors/search', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@searchDoctorsAuthorize',
]);

$api->get('/users-doctors/search/{id:[0-9]+}', [
    'action' => 'VIEW-USER',
    'uses' => 'UserController@viewDoctorsAuthorize',
]);