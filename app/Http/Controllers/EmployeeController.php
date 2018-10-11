<?php declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Employee;
use App\Http\Services\EmployeeService;
use Illuminate\View\View;


/**
 * Class EmployeeController
 *
 * @package App\Http\Controllers
 */
class EmployeeController extends Controller
{
	
	/**
	 * EmployeeController constructor.
	 *
	 * @param \App\Http\Services\EmployeeService $employeeService
	 */
	public function __construct (EmployeeService $employeeService)
    {
    	$this->service = $employeeService;
    }
	
	/**
	 * @param \App\Employee $employee
	 *
	 * @return \Illuminate\View\View
	 */
	public function showEmployeeSubordinates (Employee $employee): View
	{
		/** @var \App\Employee $employee */
		$employees = $this->service->getEmployeeSubordinates($employee);
		
		return view('employeeSubordinates')->with(compact('employees'));
	}
}
