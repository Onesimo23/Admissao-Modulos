<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CandidatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 300) as $index) {
            DB::table('candidates')->insert([
                'uuid' => Str::uuid(),
                'province_id' => $faker->numberBetween(1, 10), // Substitua 10 pelo número de províncias que você tem
                'special_need_id' => $faker->numberBetween(1, 5), // Ajuste conforme o número de necessidades especiais disponíveis
                'university_id' => $faker->numberBetween(1, 4), // Ajuste com o número de universidades
                'regime_id' => $faker->numberBetween(1, 3), // Ajuste conforme o número de regimes
                'course_id' => $faker->numberBetween(1, 10), // Ajuste com o número de cursos
                'user_id' => $faker->optional()->randomElement([null, DB::table('users')->pluck('id')->random()]),
                'surname' => $faker->lastName,
                'name' => $faker->firstName,
                'birthdate' => $faker->date('Y-m-d', '2000-01-01'),
                'nationality' => $faker->country,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'marital_status' => $faker->randomElement(['Single', 'Married', 'Divorced']),
                'document_type' => $faker->randomElement(['ID', 'Passport']),
                'document_number' => $faker->unique()->numerify('############'),
                'nuit' => $faker->optional()->numerify('###########'),
                'cell1' => $faker->phoneNumber,
                'cell2' => $faker->optional()->phoneNumber,
                'email' => $faker->optional()->email,
                'pre_university_type' => $faker->randomElement(['Secondary', 'High School']),
                'pre_university_year' => $faker->year,
                'local_exam' => $faker->city,
                'status' => $faker->numberBetween(0, 1), // Status pode ser 0 ou 1
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
