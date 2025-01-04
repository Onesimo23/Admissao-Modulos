<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regimes = [
            ['id' => 1, 'name' => 'LABORAL'],
            ['id' => 2, 'name' => 'PÓS-LABORAL'],
            ['id' => 3, 'name' => 'EAD'],
            ['id' => 4, 'name' => 'SEMI-PRESENCIAL'],
        ];

        foreach ($regimes as $regime) {
            //DB::table('regimes')->insert($regime);
            DB::table('regimes')->insert([
                'id' 	=> $regime['id'],
                'uuid' 	=> Str::uuid()->toString(),
                'name' 	=> $regime['name'],
            ]);					
        }
    }
}