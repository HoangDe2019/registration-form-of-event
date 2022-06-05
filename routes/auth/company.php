<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */
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

$api->get('/companies', [
    'action' => 'VIEW-COMPANIES',
    'uses' => 'CompanyController@search',
]);

$api->get('/company/{id:[0-9]+}', [
    'action' => 'VIEW-COMPANIES',
    'uses' => 'CompanyController@detail',
]);

$api->post('/company', [
    'action' => 'CREATE-COMPANIES',
    'uses' => 'CompanyController@create',
]);

$api->put('/company/{id:[0-9]+}', [
    'action' => 'UPDATE-COMPANIES',
    'uses' => 'CompanyController@update',
]);

$api->delete('/company/{id:[0-9]+}', [
    'action' => 'DELETE-COMPANIES',
    'uses' => 'CompanyController@delete',
]);
