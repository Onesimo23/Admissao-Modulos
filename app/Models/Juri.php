<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Juri extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'disciplina_id', 'school_id'];

    protected $guarded = [];


    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function juryDistributions()
    {
        return $this->hasMany(JuryDistribution::class);
    }
    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function monitors()
    {
        return $this->belongsToMany(Monitor::class, 'juri_monitor', 'juri_id', 'monitor_id');
    }

    public function province()
    {
        return $this->school->province_id; 
    }
}
