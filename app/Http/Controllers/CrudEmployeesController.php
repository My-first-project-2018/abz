<?php declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Department;
use App\Http\Services\DepartmentService;
use App\Http\Services\EmployeeService;
use Illuminate\Http\Request;
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
	 * @param \App\Department|null $department
	 *
	 * @return \Illuminate\View\View
	 */
	public function showEmployees (Department $department = null) : View
	{
		$departments = $this->departmentService->getAllDepartments();
		
		$currentDepartment  = $department ?: $departments->first();
		
		/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $employees */
		$employees  = $this->departmentService->getEmployeesPaginate($currentDepartment);
		
		$employees->setPath(route('paginationEmployees',['department' => $currentDepartment->slug]));
		
		$fields = $this->employeesService->getColumnsList();
		
		if(!$department){
			return \view('crudEmployees')->with(compact(['employees', 'departments', 'currentDepartment', 'fields']));
		}
		
		return \view('departmentSelected')->with(compact('employees'));
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Department          $department
	 *
	 * @return \Illuminate\View\View
	 */
	public function showOrderByEmployees (Request $request, Department $department): View
	{
		$employees = $this->departmentService->getOrderByEmployees($request,$department);
		
		return \view('departmentSelected')->with(compact('employees'));
	}
	
	/**
	 * @param \App\Department $department
	 *
	 * @return \Illuminate\View\View
	 */
	public function getPaginationEmployees (Department $department): View
	{
		/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $employees */
		$employees  = $this->departmentService->getEmployeesPaginate($department);
		
		$fields = $this->employeesService->getColumnsList();
		
		return \view('employeesItem')->with(compact(['employees','fields']));
	}
}