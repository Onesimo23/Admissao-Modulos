<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseExamSubjectsSeeder extends Seeder
{
    public function run()
    {
        $examSubjects = [
            // Biologia (id: 13)
            ['course_id' => 13, 'subject_ids' => [3, 7]], // Química, Biologia
            
            // Inglês (id: 6)
            ['course_id' => 6, 'subject_ids' => [9, 4]], // Português, Inglês
            
            // Português (id: 7)
            ['course_id' => 7, 'subject_ids' => [9, 6]], // Português, História
            
            // História (id: 5)
            ['course_id' => 5, 'subject_ids' => [6, 1]], // História, Geografia
            
            // Física (id: 11)
            ['course_id' => 11, 'subject_ids' => [5, 8]], // Matemática, Física
            
            // Gestão Aduaneira (id: 21)
            ['course_id' => 21, 'subject_ids' => [9, 5]], // Português, Matemática
            
            // Informática (id: 12)
            ['course_id' => 12, 'subject_ids' => [5, 8]], // Matemática, Física
            
            // Psicologia (id: 1)
            ['course_id' => 1, 'subject_ids' => [9, 7]], // Português, Biologia
            
            // Educação Visual (id: 17)
            ['course_id' => 17, 'subject_ids' => [5, 2]], // Matemática, Aptidão em Desenho
            
            // Ambiente e Sustentabilidade (id: 15)
            ['course_id' => 15, 'subject_ids' => [9, 1]], // Português, Geografia
            
            // Produção Agrícola (id: 23)
            ['course_id' => 23, 'subject_ids' => [3, 7]], // Química, Biologia
            
            // Ciências de Educação (id: 4)
            ['course_id' => 4, 'subject_ids' => [9, 7]], // Português, Biologia
            
            // Medicina Veterinária (id: 8)
            ['course_id' => 8, 'subject_ids' => [3, 7]], // Química, Biologia
            
            // Matemática (id: 14)
            ['course_id' => 14, 'subject_ids' => [5, 8]], // Matemática, Física
            
            // Tecnologia de Alimentos (id: 24)
            ['course_id' => 24, 'subject_ids' => [3, 7]], // Química, Biologia
            
            // Zootecnia (id: 9)
            ['course_id' => 9, 'subject_ids' => [3, 7]], // Química, Biologia
        ];

        foreach ($examSubjects as $examSubject) {
            foreach ($examSubject['subject_ids'] as $subjectId) {
                DB::table('course_exam_subjects')->insert([
                    'uuid' => Str::uuid()->toString(),
                    'course_id' => $examSubject['course_id'],
                    'exam_subject_id' => $subjectId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
