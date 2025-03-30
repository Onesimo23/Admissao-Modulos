<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
    use HasFactory;

    protected $table = 'exam_subjects'; // Nome da tabela
    protected $fillable = ['name', 'exam_date', 'arrival_time', 'start_time'];
}
