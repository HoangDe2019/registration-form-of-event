<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 5/22/2019
 * Time: 9:01 AM
 */

$api->get('/log_word', [
    'action' => 'VIEW-LOG-WORD',
    'uses' => 'LogWordController@search',
]);

$api->get('/log_word/{id:[0-9]+}', [
    'action' => 'VIEW-LOG-WORD',
    'uses' => 'LogWordController@detail',
]);

$api->get('/issues/{issue_id:[0-9]+}/log_word', [
    'action' => 'VIEW-LOG-WORD',
    'uses' => 'LogWordController@issueDetail',
]);

$api->post('/log_word', [
    'action' => 'CREATE-LOG-WORD',
    'uses' => 'LogWordController@create',
]);

$api->put('/log_word/{id:[0-9]+}', [
    'action' => 'UPDATE-LOG-WORD',
    'uses' => 'LogWordController@update',
]);


$api->delete('/log_word/{id:[0-9]+}', [
    'action' => 'DELETE-LOG-WORD',
    'uses' => 'LogWordController@delete',
]);