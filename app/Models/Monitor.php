<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monitor extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['name', 'email', 'phone', 'status'];

    // Remova o relacionamento com Juri
    // public function juris()
    // {
    //     return $this->belongsToMany(Juri::class, 'juri_has_monitors');
    // }
}
