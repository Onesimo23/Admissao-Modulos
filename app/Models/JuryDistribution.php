<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuryDistribution extends Model {
    use HasFactory;

    protected $fillable = ['candidate_id', 'room_id', 'school_id', 'province_id', 'discipline'];

    public function candidate() {
        return $this->belongsTo(Candidate::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function school() {
        return $this->belongsTo(School::class);
    }

    public function province() {
        return $this->belongsTo(Province::class);
    }
}

