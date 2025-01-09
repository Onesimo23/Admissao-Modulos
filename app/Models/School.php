<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'priority_level', 'province_id'];
    protected $guarded = [];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function juries()
    {
        return $this->hasMany(Juri::class);
    }
}
