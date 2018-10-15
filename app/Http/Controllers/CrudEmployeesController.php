<?php declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\CreateEmployeeRequest;
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
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Department|null     $department
	 *
	 * @return \Illuminate\View\View
	 */
	public function showEmployees (Request $request, Department $department) : View
	{
		$departments = $this->departmentService->getAllDepartments();
		
		/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $employees */
		$employees  = $this->departmentService->getEmployeesPaginate($department);
		
		if(!$request->ajax()){
			return \view('crudEmployees')->with(compact(['employees', 'departments', 'department']));
		}
		
		return \view('employeesItem')->with(compact('employees'));
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
		
		return \view('employeesItem')->with(compact('employees'));
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
		
		return \view('employeesItem')->with(compact(['employees']));
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function searchEmployees(Request $request) : View
	{
		$employees = $this->employeesService->searchEmployees($request);
		
		return \view('employeesItem')->with(compact(['employees']));
	}
	
	/**
	 * @param \App\Department $department
	 *
	 * @return \Illuminate\View\View
	 */
	public function showEmployeeModalForm (Department $department) : View
	{
		$positions = $this->departmentService->getPositionsDepartment($department);
		
		return \view('modalEmployee')->with(compact('positions'));
	}
	
	public function createEmployee (CreateEmployeeRequest $request)
	{
		$result = $this->employeesService->createEmployee($request);
		dd($result);
	}
}
