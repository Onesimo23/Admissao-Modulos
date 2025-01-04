<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpecialNeedsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialNeeds = [
            ['id' => 1, 'name' => 'Nenhuma'],
            ['id' => 2, 'name' => 'Deficiência Visual'],
            ['id' => 3, 'name' => 'Deficiência Auditiva'],
            ['id' => 4, 'name' => 'Deficiência Mental'],
            ['id' => 5, 'name' => 'Deficiência Físico-motora'],
            ['id' => 6, 'name' => 'Multideficiência'],	
            ['id' => 7, 'name' => 'Outra Deficiência'],				
        ];

        foreach ($specialNeeds as $specialNeed) {
            //DB::table('special_needs')->insert($specialNeed);
            DB::table('special_needs')->insert([
                'id' 			=> $specialNeed['id'],
                'uuid' 			=> Str::uuid()->toString(),
                'name' 			=> $specialNeed['name'],
            ]);				
        }
    }
}