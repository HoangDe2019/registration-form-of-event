<?php
$api->get('/departments/disease', [
    'action' => 'VIEW-DEPARTMENTS',
    'uses' => 'DepartmentController@search',
]);