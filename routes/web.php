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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'admin', 'middleware' => 'auth_admin'], function () use ($router) {
       /*admin*/
        $router->post('profile', 'AdminController@getAdmin');
        $router->post('logout', 'AdminController@logout');
        /*company*/
        $router->post('companies', 'CompanyController@index');
        $router->post('add-companies', 'CompanyController@store');
    });
});
