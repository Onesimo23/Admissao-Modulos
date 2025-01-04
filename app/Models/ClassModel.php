<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassModel extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['name', 'capacity', 'status', 'school_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function juris()
    {
        return $this->hasMany(Juri::class);
    }
}
