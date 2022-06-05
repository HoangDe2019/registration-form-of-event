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
$api->get('/module-category', [
    'action' => 'VIEW-CATEGORY-ISSUE',
    'uses' => 'ModuleCategoryController@search',
]);

$api->get('/module-category/{id:[0-9]+}', [
    'action' => 'VIEW-CATEGORY-ISSUE',
    'uses' => 'ModuleCategoryController@detail',
]);

$api->post('/module-category', [
    'action' => 'CREATE-CATEGORY-ISSUE',
    'uses' => 'ModuleCategoryController@create',
]);

$api->put('/module-category/{id:[0-9]+}', [
    'action' => 'UPDATE-CATEGORY-ISSUE',
    'uses' => 'ModuleCategoryController@update',
]);


$api->delete('/module-category/{id:[0-9]+}', [
    'action' => 'DELETE-CATEGORY-ISSUE',
    'uses' => 'ModuleCategoryController@delete',
]);