<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuryDistribution extends Model
{
    use HasFactory;

    protected $fillable = ['juri_id', 'candidate_id', 'disciplina_id'];

    public function jury()
    {
        return $this->belongsTo(Juri::class, 'juri_id');
    }
    
    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class);
    }
}

