<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniversityCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $universityCourses = [
            ['id' => 1, 'university_id' => 1, 'course_id' => 1, 'regime_id' => 1],
            ['id' => 2, 'university_id' => 1, 'course_id' => 2, 'regime_id' => 3],
            ['id' => 3, 'university_id' => 1, 'course_id' => 3, 'regime_id' => 2],
            ['id' => 4, 'university_id' => 1, 'course_id' => 4, 'regime_id' => 1],
            ['id' => 5, 'university_id' => 1, 'course_id' => 5, 'regime_id' => 1],
            ['id' => 6, 'university_id' => 1, 'course_id' => 6, 'regime_id' => 1],
            ['id' => 7, 'university_id' => 1, 'course_id' => 6, 'regime_id' => 3],
            ['id' => 8, 'university_id' => 1, 'course_id' => 7, 'regime_id' => 1],
            ['id' => 9, 'university_id' => 1, 'course_id' => 8, 'regime_id' => 1],
            ['id' => 10, 'university_id' => 1, 'course_id' => 9, 'regime_id' => 1],
            ['id' => 11, 'university_id' => 1, 'course_id' => 10, 'regime_id' => 2],
            ['id' => 12, 'university_id' => 1, 'course_id' => 11, 'regime_id' => 1],
            ['id' => 13, 'university_id' => 1, 'course_id' => 12, 'regime_id' => 1],
            ['id' => 14, 'university_id' => 1, 'course_id' => 13, 'regime_id' => 1],
            ['id' => 15, 'university_id' => 1, 'course_id' => 14, 'regime_id' => 2],
            ['id' => 16, 'university_id' => 1, 'course_id' => 14, 'regime_id' => 1],
            ['id' => 17, 'university_id' => 1, 'course_id' => 15, 'regime_id' => 1],
            ['id' => 18, 'university_id' => 1, 'course_id' => 16, 'regime_id' => 2],
            ['id' => 19, 'university_id' => 1, 'course_id' => 17, 'regime_id' => 1],
            ['id' => 20, 'university_id' => 1, 'course_id' => 18, 'regime_id' => 2],
            ['id' => 21, 'university_id' => 1, 'course_id' => 19, 'regime_id' => 2],
            ['id' => 22, 'university_id' => 1, 'course_id' => 20, 'regime_id' => 2],
            ['id' => 23, 'university_id' => 1, 'course_id' => 21, 'regime_id' => 1],
            ['id' => 24, 'university_id' => 1, 'course_id' => 22, 'regime_id' => 3],
            ['id' => 25, 'university_id' => 1, 'course_id' => 23, 'regime_id' => 1],
            ['id' => 26, 'university_id' => 1, 'course_id' => 24, 'regime_id' => 1],
            ['id' => 27, 'university_id' => 2, 'course_id' => 2, 'regime_id' => 3],
            ['id' => 28, 'university_id' => 2, 'course_id' => 6, 'regime_id' => 3],
            ['id' => 29, 'university_id' => 2, 'course_id' => 22, 'regime_id' => 3],
            ['id' => 30, 'university_id' => 3, 'course_id' => 1, 'regime_id' => 2],
            ['id' => 31, 'university_id' => 3, 'course_id' => 2, 'regime_id' => 3],
            ['id' => 32, 'university_id' => 3, 'course_id' => 3, 'regime_id' => 2],
            ['id' => 33, 'university_id' => 3, 'course_id' => 5, 'regime_id' => 2],
            ['id' => 34, 'university_id' => 3, 'course_id' => 13, 'regime_id' => 1],
            ['id' => 35, 'university_id' => 3, 'course_id' => 14, 'regime_id' => 2],
            ['id' => 36, 'university_id' => 3, 'course_id' => 15, 'regime_id' => 2],
            ['id' => 37, 'university_id' => 3, 'course_id' => 16, 'regime_id' => 2],
            ['id' => 38, 'university_id' => 3, 'course_id' => 20, 'regime_id' => 2],
            ['id' => 39, 'university_id' => 3, 'course_id' => 22, 'regime_id' => 3],
            ['id' => 40, 'university_id' => 4, 'course_id' => 20, 'regime_id' => 4],
            ['id' => 41, 'university_id' => 4, 'course_id' => 21, 'regime_id' => 4],
            ['id' => 42, 'university_id' => 4, 'course_id' => 22, 'regime_id' => 3],
        ];

        foreach ($universityCourses as $universityCourse) {
            //DB::table('university_courses')->insert($universityCourse);
            DB::table('university_courses')->insert([
                'id' 					=> $universityCourse['id'],
                'uuid' 					=> Str::uuid()->toString(),
                'university_id' 		=> $universityCourse['university_id'],
                'course_id' 			=> $universityCourse['course_id'],
                'regime_id' 			=> $universityCourse['regime_id'],				
            ]);			
        }
    }
}