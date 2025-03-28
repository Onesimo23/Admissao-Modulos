<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function universityCourses()
    {
        return $this->hasMany(UniversityCourse::class);
    }
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function juris()
    {
        return $this->belongsToMany(Juri::class, 'juri_has_courses');
    }

    public function disciplina()
    {
        return $this->hasOne(Disciplina::class, 'course_id');
    }

    public function disciplines()
    {
        return $this->hasMany(Disciplina::class, 'course_id');
    }
}
