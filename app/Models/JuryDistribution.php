<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuryDistribution extends Model {
    use HasFactory;

    protected $fillable = ['candidate_id', 'room_id', 'school_id', 'province_id', 'exam_subject_id'];

    public function candidate() {
        return $this->belongsTo(Candidate::class);
    }
    public function examSubject()
    {
        return $this->belongsTo(ExamSubject::class);
    }
    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function school() {
        return $this->belongsTo(School::class);
    }
    public function province() {
        return $this->belongsTo(Province::class);
    }
    public function jury() {
        return $this->belongsTo(Juri::class);
    }
    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function discipline() {
        return $this->belongsTo(Disciplina::class);
    }

}

