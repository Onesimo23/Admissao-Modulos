<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Garantir que o 'school_id' seja um ID válido da tabela 'schools'
        $schoolIds = DB::table('schools')->pluck('id')->toArray();

        foreach (range(1, 10) as $index) { // Gerando 10 salas fictícias
            DB::table('rooms')->insert([
                'name' => 'Sala ' . $faker->numberBetween(100, 199), // Nome da sala (ex.: "Sala 101")
                'school_id' => $faker->randomElement($schoolIds), // Atribuindo um 'school_id' válido
                'capacity' => $faker->numberBetween(20, 50), // Capacidade da sala (ex.: entre 20 e 50)
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
