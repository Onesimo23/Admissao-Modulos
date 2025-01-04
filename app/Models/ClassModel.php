<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassModel extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function juris()
    {
        return $this->hasMany(Juri::class);
    }
}
