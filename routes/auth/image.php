<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 4/22/2019
 * Time: 10:40 PM
 */
/*
$api->get('/image', [
    'action' => 'VIEW-FILE',
    'uses'   => 'InfoController@search',
]);

$api->get('/image/{id:[0-9]+}', [
    'action' => 'VIEW-FILE',
    'uses'   => 'InfoController@detail',
]);*/

$api->post('/image', [
    'action' => 'CREATE-FILE',
    'uses' => 'FileUploadController@upload',
]);