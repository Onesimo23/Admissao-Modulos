<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityCourse extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }
	
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function regime()
    {
        return $this->belongsTo(Regime::class);
    }	
}
