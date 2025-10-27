<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
    return $router->app->version();
});

// Auth routes
$router->group(['prefix' => 'api/auth'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
});

// Asset routes
$router->group(['prefix' => 'api/assets'], function () use ($router) {
    $router->get('/', 'AssetController@index');
    $router->post('/upload', 'AssetController@upload');
    $router->get('/{id}', 'AssetController@show');
});

// File serving routes
$router->get('/storage/assets/{path:.*}', 'FileController@serve');
