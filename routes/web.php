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
    $router->group(['prefix' => 'admin', 'middleware' => 'auth_api:admin_api'], function () use ($router) {
       /*admin*/
        $router->post('profile', 'AdminController@getAdmin');
        $router->post('logout', 'AdminController@logout');
        /*company*/
        $router->post('companies', 'CompanyController@index');
        $router->post('add-company', 'CompanyController@store');
        $router->post('{company}/update-company', 'CompanyController@update');
        $router->post('{company}/destroy-company', 'CompanyController@destroy');
    });
    $router->post('admin/login', 'AdminController@login');
    //Company
    $router->group(['prefix' => 'company', 'middleware' => 'auth_api:company_api'], function () use ($router) {
        $router->post('profile', 'CompanyController@getCompany');
         $router->post('{companyId}/add-employee', 'CompanyController@addEmployee');
         $router->post('{companyId}/list-employee', 'CompanyController@companyEmployee');
     });
});
