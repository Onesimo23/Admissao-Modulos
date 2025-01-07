<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monitor extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['name', 'email', 'phone', 'status', 'school_id', 'room_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    } 
    public function juris()
    {
        return $this->belongsToMany(Juri::class, 'juri_has_monitors');
    }
    public function room()
    {
        return $this->belongsTo(ClassModel::class);
    }
}
