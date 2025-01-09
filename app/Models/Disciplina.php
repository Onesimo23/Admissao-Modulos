<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }
    // No modelo Disciplina
    // No modelo Disciplina
    public function course()
    {
        return $this->belongsTo(Course::class);
    }


}