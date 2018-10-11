<?php declare( strict_types = 1 );

use App\Department;
use App\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Class SubordinateEmployeesTableSeeder
 */
class SubordinateEmployeesTableSeeder extends Seeder {
	
	private $strData;
	
	private $id = 1;
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run (): void
	{
		$this->strData = 'id,employee_id,subordinate_id'."\n";
		if(!Storage::exists('csv/subordinate.csv')){
			$departments = Department::all()->load( 'employees' );
			
			$departments->each( function ($department) {
				
				$employees = $department->employees;
				$bossDepartment = $employees->shift();
				$subordinateBoss = $employees->splice( 0, 600 );
				
				$this->writeToStrData( $bossDepartment,
					$subordinateBoss->pluck( ['id'] )->toArray() );
				
				$this->distributionEmployees( $employees, $subordinateBoss );
			} );
			
			Storage::put( 'csv/subordinate.csv', $this->strData );
		}
		
		$this->writeToDB();
	}
	
	/**
	 * @param \Illuminate\Support\Collection $employees
	 * @param \Illuminate\Support\Collection $bossDepartment
	 *
	 * @throws \Exception
	 */
	public function distributionEmployees (Collection $employees, Collection $bossDepartment): void
	{
		if($employees->isNotEmpty()){
			$subordinate = $employees->splice(0,50);
			
			$subordinateEmployees = $subordinate->chunk(5);
			$subordinateEmployees->each(function ($item) use ($bossDepartment){
				
				$boss = $bossDepartment->random();
				$this->writeToStrData($boss, $item->pluck(['id'])->toArray());
			});
			
			$this->distributionEmployees($employees, $subordinate);
		}
	}
	
	/**
	 * @param \App\Employee $boss
	 * @param array         $data
	 */
	public function writeToStrData (Employee $boss, array $data): void
	{
		$str = '';
		foreach ($data as $item){
			/** @var Employee $boss */
			$str .= $this->id++.','. $boss->id . ',' . $item . "\n";
		}
		
		$this->strData .= $str;
	}
	
	/**
	 *
	 */
	public function writeToDB (): void
	{
		
		$pdo = DB::connection()->getPdo();
		
		$pdo->exec( "LOAD DATA LOCAL INFILE '"
		            . storage_path( 'app/csv/subordinate.csv' )
		            . "' INTO TABLE subordinate_employees CHARACTER SET UTF8 FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 ROWS" );
	}
}
