<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['name', 'capacity', 'status', 'school_id', 'priority_level'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function juries()
    {
        return $this->hasMany(Juri::class);
    }
}

