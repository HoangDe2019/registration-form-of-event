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

$api->get('/departments', [
    'action' => 'VIEW-DEPARTMENTS',
    'uses' => 'DepartmentController@search',
]);

$api->get('/department/{id:[0-9]+}', [
    'action' => 'VIEW-DEPARTMENTS',
    'uses' => 'DepartmentController@detailDepartment',
]);

$api->post('/department', [
    'action' => 'CREATE-DEPARTMENTS',
    'uses' => 'DepartmentController@create',
]);

$api->put('/department/{id:[0-9]+}', [
    'action' => 'UPDATE-DEPARTMENTS',
    'uses' => 'DepartmentController@update',
]);

$api->delete('/department/{id:[0-9]+}', [
    'action' => 'DELETE-DEPARTMENTS',
    'uses' => 'DepartmentController@delete',
]);

$api->get('/departments/diseases', [
    'action' => 'VIEW-DEPARTMENTS',
    'uses' => 'DepartmentController@listDiseasesOfDepartments',
]);
