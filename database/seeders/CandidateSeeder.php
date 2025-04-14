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
        $regimes = Regime::whereIn('id', [1, 2, 3, 4])->get(); // Incluindo todos os regimes (1, 2, 3, 4)
        
        // Cursos que podem ser atribuídos ao regime 1
        $coursesForRegime1 = Course::whereIn('id', [13, 6, 7, 5, 11, 21, 12, 1, 17, 15, 23, 4, 8, 14, 24, 9])->get();

        // Cursos que podem ser atribuídos a outros regimes (2, 3 ou 4)
        $coursesForOtherRegimes = Course::whereNotIn('id', [13, 6, 7, 5, 11, 21, 12, 1, 17, 15, 23, 4, 8, 14, 24, 9])->get();

        for ($i = 0; $i < 500; $i++) {
            $province = $provinces->random();
            $specialNeed = $specialNeeds->random();
            $university = $universities->random();
            $regime = $regimes->random(); // Selecionar aleatoriamente entre todos os regimes (1, 2, 3, 4)

            // Se o regime for 1 (LABORAL), atribui um curso da lista específica
            if ($regime->id == 1) {
                $course = $coursesForRegime1->random();
            } else {
                // Caso contrário, atribui um curso da lista dos outros regimes
                $course = $coursesForOtherRegimes->random();
            }

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
                'regime_id' => $regime->id, // Regime aleatório entre 1, 2, 3 ou 4
                'course_id' => $course->id, // Curso atribuído conforme o regime
                'status' => 1, // Status inicial
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
