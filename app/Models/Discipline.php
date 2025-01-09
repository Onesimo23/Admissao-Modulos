<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    protected $fillable = [
        'courses_id',
        'discipline1',
        'discipline2',
        'pesodiscipline1',
        'pesodiscipline2',
    ];

    /**
     * Relacionamento com o modelo Course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'courses_id');
    }
}
