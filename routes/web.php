<?php declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'cors'], function (){
	
	Route::get('/',['as' => 'showDepartments', 'uses' => 'DepartmentController@showDepartments']);
	
	Route::get('department-employees/{department}',['as' => 'departmentEmployees', 'uses' => 'DepartmentController@showDepartmentBossAndSubordinate']);
	
	Route::get('employee-subordinates/{employee}',['as' => 'employeeSubordinates', 'uses' => 'EmployeeController@showEmployeeSubordinates']);
	
	Route::post('rewrite-boss-employee',['as' => 'rewriteBossEmployee', 'uses' => 'EmployeeController@rewriteBossEmployee']);
	
	Route::post('/login', ['as' => 'login', 'uses'=>'Auth\LoginController@login']);
	
	Route::get('/logout', ['as' => 'logout', 'uses'=>'Auth\LoginController@logout']);

//	Route::group(['middleware' => ['auth', 'cors']], function (){
		
		Route::get('employees-department/{department}', ['as' => 'employeesDepartment', 'uses' => 'CrudEmployeesController@showEmployees']);
		
		Route::get('orderBy-employees/{department}', ['as' => 'orderByEmployees', 'uses' => 'CrudEmployeesController@showOrderByEmployees']);
		
		Route::post('search-employees', ['as' => 'searchEmployees', 'uses' => 'CrudEmployeesController@searchEmployees']);
		
		Route::match(['get', 'post'],'create-employee/{department}', ['as' => 'createEmployee', 'uses' => 'CrudEmployeesController@createEmployee']);
		
		Route::group(['prefix' => 'create-employee/{department}'],function (){
			
			Route::get('/', ['as' => 'showEmployeeModalForm', 'uses' => 'CrudEmployeesController@showEmployeeModalForm']);
			
			Route::post('/', ['as' => 'createEmployee', 'uses' => 'CrudEmployeesController@createEmployee']);
			
		});
		
//	});
});
