<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['disciplina_id', 'exam_date', 'start_time', 'end_time'];

    public function discipline()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function juryDistributions()
    {
        return $this->hasMany(JuryDistribution::class);
    }
}

