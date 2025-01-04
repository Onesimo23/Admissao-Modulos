<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allocation extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function juri()
    {
        return $this->belongsTo(Juri::class);
    }
}
