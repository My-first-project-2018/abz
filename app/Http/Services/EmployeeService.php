<?php declare( strict_types = 1 );

namespace App\Http\Services;

use App\Employee;
use App\Http\Repositories\EmployeeRepository;
use Illuminate\Database\Eloquent\Collection;


/**
 * Class EmployeeService
 *
 * @package App\Http\Services
 */
class EmployeeService {
	
	private $repository;
	
	/**
	 * DepartmentService constructor.
	 *
	 * @param \App\Http\Repositories\EmployeeRepository $repository
	 */
	public function __construct (EmployeeRepository $repository)
	{
		$this->repository = $repository;
	}
	
	/**
	 * @param \App\Employee $employee
	 *
	 * @return \App\Employee[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getEmployeeSubordinates (Employee $employee) : Collection
	{
		return $employee->subordinate->load('position');
	}
}