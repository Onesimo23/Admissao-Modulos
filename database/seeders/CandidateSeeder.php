<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Province;
use App\Models\SpecialNeed;
use App\Models\University;
use App\Models\Course;
use App\Models\Regime;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = Province::all();
        $specialNeeds = SpecialNeed::all();
        $universities = University::all();
        $courses = Course::all();
        $regime = Regime::where('name', 'Laboral')->first();

        for ($i = 0; $i < 500; $i++) {
            $province = $provinces->random();
            $specialNeed = $specialNeeds->random();
            $university = $universities->random();
            $course = $courses->random();

            $candidate = Candidate::create([
                'surname' => strtoupper(fake()->lastName),
                'name' => strtoupper(fake()->firstName),
                'birthdate' => Carbon::parse(fake()->date),
                'nationality' => 'MOÇAMBICANA',
                'gender' => fake()->randomElement(['MASCULINO', 'FEMININO']),
                'marital_status' => fake()->randomElement(['SOLTEIRO(A)', 'CASADO(A)', 'VIUVO(A)', 'DIVORCIADO(A)']),
                'province_id' => $province->id,
                'special_need_id' => $specialNeed->id,
                'document_type' => 'BI',
                'document_number' => strtoupper(Str::random(10)),
                'nuit' => strtoupper(Str::random(9)),
                'cell1' => fake()->numerify('#########'),
                'cell2' => fake()->numerify('#########'),
                'email' => fake()->email,
                'pre_university_type' => '12ª - GRUPO A',
                'pre_university_year' => fake()->year,
                'local_exam' => $province->id,
                'university_id' => $university->id,
                'regime_id' => $regime->id,
                'course_id' => $course->id,
                'status' => 1, // Initial status
            ]);

            $user = User::create([
                'name' => $candidate->name . ' ' . $candidate->surname,
                'document_number' => $candidate->document_number,
                'username' => $candidate->id,
                'password' => bcrypt(trim($candidate->cell1)),
                'role' => 'user',
            ]);

            $candidate->update([
                'user_id' => $user->id,
            ]);
        }
    }
}
