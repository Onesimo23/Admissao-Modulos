<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SchoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Garantir que o 'province_id' seja um ID válido da tabela 'provinces'
        $provinceIds = DB::table('provinces')->pluck('id')->toArray();

        foreach (range(1, 10) as $index) { // Gerando 10 escolas fictícias
            DB::table('schools')->insert([
                'name' => $faker->company,
                'province_id' => $faker->randomElement($provinceIds), // Atribuindo um 'province_id' válido
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
