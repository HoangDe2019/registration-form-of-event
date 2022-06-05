<?php
//e cách ra như v là chết a luôn :(
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

$router->get('/', function () use ($router) {
    return $router->app->version() . " - HOSPITAL MANAGERS @ COPPY RIGHT ThaoB1706869";
});
// Authorization
$router->group(['prefix' => 'auth', 'namespace' => 'Auth', 'middleware' => ['cors2', 'trimInput']], function ($router) {
    // Auth
    $router->post('/login', "AuthController@authenticate");
    $router->post('/user-login', "AuthController@userLogin");
    $router->post('/user-register', "AuthController@userRegister");
    $router->get('/token', "AuthController@checkToken");
    $router->get('/logout', "AuthController@logout");
    $router->get('/forget-password', "AuthController@forgetPassword");
    $router->post('/reset-password', "AuthController@resetPassword");
});

$api = app('Dingo\Api\Routing\Router');

// Normal API
require __DIR__ . '/normal/api_route.php';
// Authorize API
require __DIR__ . '/auth/api_route.php';
