<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use PHPUnit\TextUI\XmlConfiguration\Group;

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

$router->group(['prefix' => 'file'], function () use ($router) {
    $router->get('/', 'PersonelController@getFile');
    $router->post('/upload', 'PersonelController@upload');
});
$router->group(['prefix' => 'generate-api-wso'], function () use ($router) {
    $router->get('/', 'GeneratorController@testHelper');
});
