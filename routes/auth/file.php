<?php

$api->get('/files', [
    'action' => 'VIEW-FILE',
    'uses' => 'FilesController@search',
]);


$api->get('/files/{id:[0-9]+}', [
    'action' => 'VIEW-FILE',
    'uses' => 'FilesController@detail',
]);

$api->get('/file/download/{id:[0-9]+}', [
    'action' => 'DOWNLOAD-FILE',
    'uses' => 'FilesController@download',
]);

$api->post('/files/upload', [
    'action' => 'UPLOAD-FILE',
    'uses' => 'FilesController@upload',
]);

$api->post('/files', [
    'action' => 'CREATE-FILE',
    'uses' => 'FilesController@create',
]);

$api->put('/files/{id:[0-9]+}', [
    'action' => 'UPDATE-FILE',
    'uses' => 'FilesController@update',
]);

$api->put('/files/{id:[0-9]+}/moveFile', [
    'action' => 'UPDATE-FILE',
    'uses' => 'FilesController@moveFile',
]);

$api->delete('/files/{id:[0-9]+}', [
    'action' => 'DELETE-FILE',
    'uses' => 'FilesController@delete',
]);

$api->get('/files/history', [
    'action' => 'DOWNLOAD-FILE',
    'uses' => 'FilesController@log_file',
]);

$api->get('/files/log-file', [
    'action' => 'VIEW-FILE',
    'uses' => 'FilesController@fileLogAction',
]);
$api->post('/zipfile', [
    'action' => '',
    'uses' => 'FilesController@ZipFile',
]);