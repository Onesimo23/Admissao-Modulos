<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExamSubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            [
                'name' => 'Geografia',
                'exam_date' => '2025-01-21',
                'arrival_time' => '07:30',
                'start_time' => '08:00'
            ],
            [
                'name' => 'Aptidão em Desenho',
                'exam_date' => '2025-01-21',
                'arrival_time' => '07:30',
                'start_time' => '08:00'
            ],
            [
                'name' => 'Química',
                'exam_date' => '2025-01-21',
                'arrival_time' => '10:00',
                'start_time' => '10:30'
            ],
            [
                'name' => 'Inglês',
                'exam_date' => '2025-01-22',
                'arrival_time' => '07:30',
                'start_time' => '08:00'
            ],
            [
                'name' => 'Matemática',
                'exam_date' => '2025-01-22',
                'arrival_time' => '10:00',
                'start_time' => '10:30'
            ],
            [
                'name' => 'História',
                'exam_date' => '2025-01-23',
                'arrival_time' => '07:30',
                'start_time' => '08:00'
            ],
            [
                'name' => 'Biologia',
                'exam_date' => '2025-01-23',
                'arrival_time' => '07:30',
                'start_time' => '08:00'
            ],
            [
                'name' => 'Física',
                'exam_date' => '2025-01-23',
                'arrival_time' => '10:00',
                'start_time' => '10:30'
            ],
            [
                'name' => 'Português',
                'exam_date' => '2025-01-24',
                'arrival_time' => '07:30',
                'start_time' => '08:00'
            ],
        ];

        foreach ($subjects as $subject) {
            DB::table('exam_subjects')->insert([
                'uuid' 			=> Str::uuid()->toString(),
                'name' 			=> $subject['name'],
                'exam_date' 	=> $subject['exam_date'],
                'arrival_time' 	=> $subject['arrival_time'],
                'start_time' 	=> $subject['start_time'],
                'created_at' 	=> now(),
                'updated_at' 	=> now(),
            ]);
        }
    }
}
