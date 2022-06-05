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

$api->get('/faculties', [
    'action' => 'VIEW-faculty',
    'uses' => 'FacultyController@search',
]);

$api->get('/faculty/{id:[0-9]+}', [
    'action' => 'VIEW-faculty',
    'uses' => 'FacultyController@detailFaculty',
]);

$api->post('/faculty', [
    'action' => 'CREATE-faculty',
    'uses' => 'FacultyController@create',
]);

$api->put('/faculty/{id:[0-9]+}', [
    'action' => 'UPDATE-faculty',
    'uses' => 'FacultyController@update',
]);

$api->delete('/faculty/{id:[0-9]+}', [
    'action' => 'DELETE-faculty',
    'uses' => 'FacultyController@delete',
]);
