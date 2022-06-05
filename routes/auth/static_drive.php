<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 5/30/2019
 * Time: 9:12 PM
 */

$api->get('/static-drive', [
    'action' => '',
    'uses' => 'FolderController@staticDrive',
]);