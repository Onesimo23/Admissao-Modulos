<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = DB::table('schools')->get();

        foreach ($schools as $school) {
            for ($i = 1; $i <= 10; $i++) {
                DB::table('rooms')->insert([
                    'name' => 'Sala ' . $i,
                    'capacity' => rand(20, 50),
                    'status' => true,
                    'priority_level' => $i <= 5 ? 1 : 2,
                    'school_id' => $school->id,
                ]);
            }
        }
    }
}
