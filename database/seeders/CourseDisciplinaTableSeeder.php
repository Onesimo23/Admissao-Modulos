<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseDisciplinaTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('course_disciplina')->insert([
            // Curso 1 tem as disciplinas 1 e 2
            ['course_id' => 1, 'disciplina_id' => 1],
            ['course_id' => 1, 'disciplina_id' => 2],

            // Curso 2 tem as disciplinas 3 e 4
            ['course_id' => 2, 'disciplina_id' => 3],
            ['course_id' => 2, 'disciplina_id' => 4],

            // Curso 3 tem as disciplinas 1 e 3
            ['course_id' => 3, 'disciplina_id' => 1],
            ['course_id' => 3, 'disciplina_id' => 3],
        ]);
    }
}
