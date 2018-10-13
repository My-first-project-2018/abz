<?php declare( strict_types = 1 );

namespace App\Http\Services;

use App\Department;
use App\Employee;
use App\Http\Repositories\DepartmentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class DepartmentService
 *
 * @package App\Http\Services
 */
class DepartmentService {
	
	private $repository;
	
	/**
	 * DepartmentService constructor.
	 *
	 * @param \App\Http\Repositories\DepartmentRepository $repository
	 */
	public function __construct (DepartmentRepository $repository)
	{
		$this->repository = $repository;
	}
	
	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getDepartments () : Collection
	{
		return $this->repository->allWithCountEmployees();
	}
	
	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getAllDepartments () : Collection
	{
		return $this->repository->all();
	}
	
	/**
	 * @param \App\Department $department
	 *
	 * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
	 */
	public function getDepartmentBossAndSubordinate (Department $department) : ?Employee
	{
		$departmentBoss = $department->employees()
	                                 ->with(['subordinate.position','position'])
	                                 ->whereNotExists(function ($query){
		                                 $query->select(\DB::raw(1))
		                                       ->from('subordinate_employees')
		                                       ->whereRaw('subordinate_employees.subordinate_id = employees.id');})
	                                 ->first();
		return $departmentBoss;
	}
	
	/**
	 * @param \App\Department $department
	 *
	 * @param int             $countPage
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getEmployeesPaginate (Department $department, $countPage = 50): LengthAwarePaginator
	{
		$departmentEmployees = $department->employees()
										  ->with(['position','boss'])
			                              ->paginate($countPage);
		
		return $departmentEmployees;
	}
	
	/**
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function getFirstDepartment (): Model
	{
		return $this->repository->getFirstModel();
	}
}