<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Juri extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }
    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'juri_has_courses');
    }

    public function monitors()
    {
        return $this->belongsToMany(Monitor::class, 'juri_has_monitors');
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }
}
