<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RegimesSeeder::class);
		$this->call(ProvincesSeeder::class);		
		$this->call(UniversitiesSeeder::class);    
        // $this->call(DisciplinasTableSeeder::class);
		$this->call(CoursesSeeder::class);
		$this->call(UniversityCoursesSeeder::class);
		$this->call(SpecialNeedsSeeder::class);
        $this->call(SchoolsSeeder::class);
        $this->call(RoomsSeeder::class);
        $this->call(CandidateSeeder::class);
        // $this->call(UsersTableSeeder::class);
        // $this->call(CandidatesTableSeeder::class);
        // $this->call(SchoolsTableSeeder::class);
        // $this->call(RoomsTableSeeder::class);
		// User::factory(10)->create();

        //User::factory()->create([
        //    'name' => 'Test User',
        //    'email' => 'test@example.com',
        //]);
    }
}
