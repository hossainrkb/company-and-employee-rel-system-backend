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
    /*Admin Route*/
    $router->group(['prefix' => 'admin', 'middleware' => 'auth_api:admin_api'], function () use ($router) {
        $router->post('profile', 'AdminController@getAdmin');
        $router->post('logout', 'AdminController@logout');
        $router->post('companies', 'CompanyController@index');
        $router->post('add-company', 'CompanyController@store');
        $router->post('{company}/update-company', 'CompanyController@update');
        $router->post('{company}/destroy-company', 'CompanyController@destroy');
    });
    $router->post('admin/login', 'AdminController@login');
    /*Company Route */
    $router->group(['prefix' => 'company', 'middleware' => 'auth_api:company_api'], function () use ($router) {
        $router->post('profile', 'CompanyController@getCompany');
         $router->post('{companyId}/dashboard', 'CompanyController@companyDashboard');
         $router->post('{companyId}/add-employee', 'CompanyController@addEmployee');
         $router->post('{companyId}/list-employee', 'CompanyController@companyEmployee');
         $router->post('{companyId}/leave-application-employee', 'EmpLeaveDetailController@comEmpLeaveStore');
         $router->post('{companyId}/pending-application-employee', 'EmpLeaveDetailController@pendingLeaveList');
         $router->post('{companyId}/leave/{leaveId}/decline', 'EmpLeaveDetailController@empLeaveStatusDecline');
         $router->post('{companyId}/leave/{leaveId}/approve', 'EmpLeaveDetailController@empLeaveStatusApprove');
         $router->post('{companyId}/current-month-attendance-summary', 'EmpAttendanceController@attendanceDetaiilsCurrentMonth');
         $router->post('{companyId}/attendance', 'EmpAttendanceController@empAttendanceDetails');
         $router->post('{companyId}/{employeeId}/{month}/{year}/emp-stat-details', 'EmployeeController@empStatDetails');
         $router->post('{companyId}/emp-stat/create', 'EmployeeController@empStatCreate');
     });
    /*Employee Route */
    // $router->group(['prefix' => 'employee', 'middleware' => 'auth_api:employee_api'], function () use ($router) {
    $router->group(['prefix' => 'employee'], function () use ($router) {
        $router->post('profile', 'EmployeeController@getEmployee');
        $router->post('{employeeId}/check-in', 'EmpAttendanceController@checkInStore');
        $router->post('{employeeId}/{attendanceId}/check-out', 'EmpAttendanceController@checkOutStore');
     });
});
