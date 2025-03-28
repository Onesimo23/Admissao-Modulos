<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'disciplina1',
        'disciplina2',
        'peso1',
        'peso2',
        'horario_disciplina1',
        'horario_disciplina2',
    ];

    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
