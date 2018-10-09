<?php declare( strict_types = 1 );

use App\Employee;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
    
//	    factory(\App\Employee::class,50000)->create();
         $this->call(UsersTableSeeder::class);
    }
}
