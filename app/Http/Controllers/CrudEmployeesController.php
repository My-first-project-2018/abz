<?php declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Http\Services\DepartmentService;
use App\Http\Services\EmployeeService;
use Illuminate\View\View;


/**
 * Class CrudEmployeesController
 *
 * @package App\Http\Controllers
 */
class CrudEmployeesController extends Controller
{
	private $departmentService;
	
	private $employeesService;
	
	/**
	 * CrudEmployeesController constructor.
	 *
	 * @param \App\Http\Services\DepartmentService $departmentService
	 * @param \App\Http\Services\EmployeeService   $employeeService
	 */
	public function __construct (DepartmentService $departmentService, EmployeeService $employeeService)
    {
    	$this->departmentService = $departmentService;
    	$this->employeesService  = $employeeService;
    }
	
	
	/**
	 * @return \Illuminate\View\View
	 */
	public function showEmployees () : View
	{
		$departments = $this->departmentService->getAllDepartments();
		
		$currentDepartment  =  $departments->first();
		
		/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $employees */
		$employees  = $this->departmentService->getEmployeesPaginate($currentDepartment);
		
		return \view('crudEmployees')->with(compact(['employees', 'departments', 'currentDepartment']));
	}
}
