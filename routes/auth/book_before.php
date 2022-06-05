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
$api->get('/bookbefores', [
    'action' => 'VIEW-BOOK-BEFORE',
    'uses' => 'BookBeforeController@search',
]);

$api->get('/bookbefore/info', [
    'action' => 'VIEW-BOOK-BEFORE',
    'uses' => 'BookBeforeController@getinfoBookBefore',
]);

$api->get('/bookbefore/dr/patient/{id:[0-9]+}', [
    'action' => 'VIEW-BOOK-BEFORE',
    'uses' => 'BookBeforeController@getinfoBookBeforeofPatienAndDortor',
]);

$api->get('/bookbefore/dr/patientBookNear/{id:[0-9]+}', [
    'action' => 'VIEW-BOOK-BEFORE',
    'uses' => 'BookBeforeController@getinfoBookBeforeofPatienAndDortorNear',
]);

$api->get('/bookbefore/booking-dr', [
    'action' => 'VIEW-BOOK-BEFORE',
    'uses' => 'BookBeforeController@getinfoBookBeforeDoctor',
]);

$api->post('/bookbefore', [
    'action' => 'CREATE-BOOK-BEFORE',
    'uses' => 'BookBeforeController@create',
]);

//
$api->put('/bookbefore/{id:[0-9]+}', [
    'action' => 'UPDATE-BOOK-BEFORE',
    'uses' => 'BookBeforeController@update',
]);

//for patient bby user login account
$api->put('/bookbefore/info-cancel/{id:[0-9]+}', [
    'action' => 'UPDATE-BOOK-BEFORE',
    'uses' => 'BookBeforeController@delete_refuse',
]);
//
$api->delete('/bookbefore/{id:[0-9]+}', [
    'action' => 'DELETE-BOOK-BEFORE',
    'uses' => 'BookBeforeController@delete',
]);
$api->delete('/bookbefore/info/{id:[0-9]+}', [
    'action' => 'DELETE-BOOK-BEFORE',
    'uses' => 'BookBeforeController@delete',
]);

//CoutPatient When book
$api->get('/bookbefores/count/patients', [
    'action' => 'VIEW-COUNT-PATIENTS-BOOK-BEFORE',
    'uses' => 'BookBeforeController@countBookBeforePatient',
]);

$api->get('/bookbefores/count/timestamp/patients', [
    'action' => 'VIEW-COUNT-PATIENTS-BOOK-BEFORE',
    'uses' => 'BookBeforeController@countBookBeforePatientToday',
]);

$api->get('/bookbefores/count-timestamp', [
    'action' => 'VIEW-BOOK-BEFORE',
    'uses' => 'BookBeforeController@countBookBeforeToday',
]);

$api->get('/bookbefores/count-timestamp/doctor', [
    'action' => 'VIEW-BOOK-BEFORE',
    'uses' => 'BookBeforeController@countBookBeforeTodayofDoctors',
]);