<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseExamSubject extends Model
{
    use HasFactory;

    protected $table = 'course_exam_subjects'; // Nome da tabela
    protected $fillable = ['uuid', 'course_id', 'exam_subject_id'];

    // Relação com Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relação com ExamSubject
    public function examSubject()
    {
        return $this->belongsTo(ExamSubject::class, 'exam_subject_id');
    }
}
