<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'school_id', 'capacity'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function juries()
    {
        return $this->hasMany(Juri::class);
    }
}

