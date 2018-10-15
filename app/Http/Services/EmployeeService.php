<?php declare( strict_types = 1 );

namespace App\Http\Services;

use App\Department;
use App\Employee;
use App\Http\Repositories\EmployeeRepository;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\RewriteBossEmployeeRequest;
use App\Position;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class EmployeeService
 *
 * @package App\Http\Services
 */
class EmployeeService {
	
	private $repository;
	private $positionModal;
	private $imageService;
	
	/**
	 * DepartmentService constructor.
	 *
	 * @param \App\Http\Repositories\EmployeeRepository $repository
	 * @param \App\Position                             $position
	 * @param \App\Http\Services\LoadImageService       $imageService
	 */
	public function __construct (EmployeeRepository $repository, Position $position, LoadImageService $imageService)
	{
		$this->repository    = $repository;
		$this->positionModal = $position;
		$this->imageService  = $imageService;
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
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 * @throws \App\Exceptions\ErrorUploadImageException
	 */
	public function createEmployee (CreateEmployeeRequest $request): Model
	{
		$data     = $this->getDataRequest($request);
		
		$employee = $this->repository->create($data);
		
		/** @var Employee $boss */
		$boss     = $this->repository->onlyHash($request->get('boss'));
		
		$boss->subordinate()->attach($employee);
		
		return $employee;
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array
	 * @throws \App\Exceptions\ErrorUploadImageException
	 */
	private function getDataRequest(Request $request): array
	{
		$data     = $request->only(['first_name', 'last_name', 'data_reception', 'salary']);
		$data['img'] = $this->imageService->upload($request);
		$data['position_id'] = $this->positionModal->whereHash($request->get('position'))->first()->id;
		
		return $data;
	}
	
	
}