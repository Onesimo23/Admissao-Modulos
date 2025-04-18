<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monitor extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['name', 'email', 'phone', 'status', 'province_id'];

    public function juris()
    {
        return $this->belongsToMany(Juri::class, 'juri_monitor', 'monitor_id', 'juri_id');
    }
    
}
