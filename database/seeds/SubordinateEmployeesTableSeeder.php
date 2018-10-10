<?php declare( strict_types = 1 );

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Class SubordinateEmployeesTableSeeder
 */
class SubordinateEmployeesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
	    $departments = \App\Department::all()->load('employees');
	    $departments->each(function ($department){
		    $data = 'employee_id,subordinate_id' . "\n";
		    /** @var Collection $employees */
		    $employees = $department->employees;
		    /** @var \App\Employee $bossDepartment */
		    $bossDepartment = $employees->shift();
		    $subordinateEmployees = $employees->splice(0,random_int(50,100));
		    $subordinateEmployees->each(function ($subordinate) use ($bossDepartment,&$data){
		    	$data .= $bossDepartment->id . ',' . $subordinate->id . "\n";
		    });
		 
		    $this->attachEmployees($employees,$subordinateEmployees,$data);
	    });
	    
    }
	
	
	/**
	 * @param \Illuminate\Support\Collection $employees
	 * @param \Illuminate\Support\Collection $subordinateEmployees
	 * @param string                         $data
	 */
	public function attachEmployees (Collection $employees, Collection $subordinateEmployees, string $data): void
    {
	    $subordinateEmployees->each(function ($employee) use ($employees, &$data){
		    $newSubordinateEmployees = $employees->splice(0,random_int(50,500));
		
		    $newSubordinateEmployees->each(function ($subordinate) use ($employee,&$data){
			    $data .= $employee->id . ',' . $subordinate->id . "\n";
		    });
		    
		    $this->attachEmployees($employees,$newSubordinateEmployees,$data);
	    });
	    Storage::put( 'csv/subordinate.csv', $data );
    }
}
