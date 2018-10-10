<?php declare( strict_types = 1 );

use App\Position;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Faker\Factory;

/**
 * Class EmployeesTableSeeder
 */
class EmployeesTableSeeder extends Seeder {
	
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function run (): void
	{
		\factory(\App\Employee::class)->create();
		/** @var Faker\Generator faker */
		$faker = Factory::create( 'ru_RU' );
		$countPositions = Position::all()->count();
		$data = 'id,hash,first_name,last_name,position_id,data_reception,salary' . "\n";
		for ($i = 2; $i <= 50000; $i ++) {
			
			$dataReception = Carbon::now()->subYear( random_int( 1, 5 ) )
									      ->subMonth(random_int( 1, 12 ))
									      ->subDay(random_int( 1, 30 ))
									      ->toDateString();
				
			$data .= $i . ',' . Uuid::uuid4()->toString() . ',"'
			         . $faker->firstName . '"' . ',"' . $faker->lastName . '",'
			         . random_int( 1, $countPositions ) . ',' . $dataReception
			         . ','.random_int( 300, 1500 ) . "\n";
			
		}
		Storage::put( 'csv/employees.csv', $data );
		
		$pdo = DB::connection()->getPdo();
		
		$pdo->exec( "LOAD DATA LOCAL INFILE '"
		            . storage_path( 'app/csv/employees.csv' )
		            . "' INTO TABLE employees CHARACTER SET UTF8 FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 ROWS" );
		
	}
}
