<?php
$api->post('/time-of-day', [
    'action' => 'POST-TIME-OF-DAY',
    'uses' => 'TimeOfDayController@create',
]);

$api->put('/time-of-day/{id:[0-9]+}', [
    'action' => 'PUT-TIME-OF-DAY',
    'uses' => 'TimeOfDayController@update',
]);

$api->delete('/time-of-day/{id:[0-9]+}', [
    'action' => 'DELETE-TIME-OF-DAY',
    'uses' => 'TimeOfDayController@delete',
]);