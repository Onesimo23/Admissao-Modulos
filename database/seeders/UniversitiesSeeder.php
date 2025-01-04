<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniversitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $universities = [
            ['id' => 1, 'province_id' => 3, 'name' => 'Unisave-Chongoene'],
            ['id' => 2, 'province_id' => 4, 'name' => 'Unisave-Maxixe'],
            ['id' => 3, 'province_id' => 4, 'name' => 'Unisave-Massinga'],
            ['id' => 4, 'province_id' => 3, 'name' => 'Centro de Recursos da Manhiça'],
        ];

        foreach ($universities as $university) {
            //DB::table('universities')->insert($university);
            DB::table('universities')->insert([
                'id' 			=> $university['id'],
                'uuid' 			=> Str::uuid()->toString(),
                'province_id' 	=> $university['province_id'],				
                'name' 			=> $university['name'],
            ]);				
        }
    }
}