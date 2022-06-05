<?php
$api->get('/time-of-day', [
    'action' => 'VIEW-TIME-OF-DAY',
    'uses' => 'TimeOfDayController@search',
]);

$api->get('/time-of-day/{id:[0-9]+}', [
    'action' => 'VIEW-TIME-OF-DAY',
    'uses' => 'TimeOfDayController@detailed',
]);