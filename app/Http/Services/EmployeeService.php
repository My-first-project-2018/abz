<?php declare( strict_types = 1 );

namespace App\Http\Services;

use App\Employee;
use App\Http\Repositories\EmployeeRepository;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\RewriteBossEmployeeRequest;
use App\Position;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Class EmployeeService
 *
 * @package App\Http\Services
 */
class EmployeeService {
	
	private $repository;
	private $positionModal;
	
	/**
	 * DepartmentService constructor.
	 *
	 * @param \App\Http\Repositories\EmployeeRepository $repository
	 * @param \App\Position                             $position
	 */
	public function __construct (EmployeeRepository $repository, Position $position)
	{
		$this->repository    = $repository;
		$this->positionModal = $position;
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
	 * @param \Illuminate\Http\Request $request
	 *
	 * @param int                      $countPage
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function searchEmployees (Request $request, $countPage = 20) : LengthAwarePaginator
	{
		$employees = $this->repository->getByFieldsLike($request->get('field'), $request->get('value'));
		
		$employees = $employees->with('position')->paginate($countPage);
	
		return $employees;
	}
	
	/**
	 * @param \App\Http\Requests\CreateEmployeeRequest $request
	 */
	public function createEmployee (CreateEmployeeRequest $request): void
	{
		$data     = $this->getDataRequest($request);
		$boss     = $this->repository->onlyHash($request->get('boss'));
		dd($request);
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	private function getDataRequest(Request $request): array
	{
		$data     = $request->only(['first_name', 'last_name', 'data_reception', 'salary']);
		
		$data['position_id'] = $this->positionModal->whereHash($request->get('hash'))->id;
		
		return $data;
	}
}