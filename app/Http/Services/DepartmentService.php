<?php declare( strict_types = 1 );

namespace App\Http\Services;

use App\Department;
use App\Http\Repositories\DepartmentRepository;
use Illuminate\Database\Eloquent\Collection;


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
		return $this->repository->all(); ;
	}
	
	public function getDepartmentEmployees (Department $department)
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
}