<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisciplinasTableSeeder extends Seeder
{
    public function run()
    {
        // Definindo os IDs dos cursos
        $courseIds = [1, 2, 3];

        // Dados fictícios de disciplinas
        $disciplinas = [
            ['disciplina1' => 'Geografia', 'disciplina2' => 'Aptidão em Desenho', 'peso1' => rand(1, 10), 'peso2' => rand(1, 10)],
            ['disciplina1' => 'Química', 'disciplina2' => 'Inglês', 'peso1' => rand(1, 10), 'peso2' => rand(1, 10)],
            ['disciplina1' => 'Matemática', 'disciplina2' => 'História', 'peso1' => rand(1, 10), 'peso2' => rand(1, 10)],
            ['disciplina1' => 'Biologia', 'disciplina2' => 'Física', 'peso1' => rand(1, 10), 'peso2' => rand(1, 10)],
            ['disciplina1' => 'Português', 'disciplina2' => 'Geografia', 'peso1' => rand(1, 10), 'peso2' => rand(1, 10)],
        ];

        // Verificar se existem cursos com os IDs 1, 2, 3
        foreach ($courseIds as $courseId) {
            // Verificar se o curso com o ID existe
            $course = DB::table('courses')->find($courseId);
            if (!$course) {
                $this->command->info("Curso com ID {$courseId} não encontrado.");
                continue;
            }

            // Inserir as disciplinas para o curso
            foreach ($disciplinas as $disciplina) {
                DB::table('disciplinas')->insert([
                    'course_id' => $courseId, // Associa a disciplina ao curso com o ID específico
                    'disciplina1' => $disciplina['disciplina1'],
                    'disciplina2' => $disciplina['disciplina2'],
                    'peso1' => $disciplina['peso1'],
                    'peso2' => $disciplina['peso2'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Disciplinas inseridas com course_id específicos (1, 2, 3).');
    }
}
