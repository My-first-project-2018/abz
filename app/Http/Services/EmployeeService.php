<?php declare( strict_types = 1 );

namespace App\Http\Services;

use App\Employee;
use App\Http\Repositories\EmployeeRepository;
use App\Http\Requests\RewriteBossEmployeeRequest;
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
		return $employee->subordinate->load(['position','subordinate']);
	}
	
	/**
	 * @param \App\Http\Requests\RewriteBossEmployeeRequest $request
	 *
	 * @return bool
	 */
	public function rewriteBossEmployee (RewriteBossEmployeeRequest $request): bool
	{
		/** @var Employee $newBoss */
		$newBoss  = $this->repository->onlyHash($request->get('newBoss'));
		
		/** @var Employee $employee */
		$employee = $this->repository->onlyHash($request->get('employee'));
		
		$employee->boss->first()->subordinate()->detach($employee);
		
		$newBoss->subordinate()->attach($employee);
		
		return true;
	}
	
	/**
	 * @return array
	 */
	public function getColumnsList () : array
	{
		return $this->repository->getColumnsList();
	}
	

}