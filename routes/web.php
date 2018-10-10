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

use Illuminate\Database\Eloquent\Collection;

Route::get('/', function () {
	$departments = \App\Department::all()->load('employees');
	$departments->each(function ($department){
		/** @var Collection $employees */
		$employees = $department->employees;
		/** @var \App\Employee $bossDepartment */
		$bossDepartment = $employees->shift();
		$subordinateEmployees = $employees->splice(0,random_int(100,500));
		$bossDepartment->subordinate()->attach($subordinateEmployees);
		dd($subordinateEmployees);
	});
    return view('welcome');
});
