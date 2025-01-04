<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }
	
    protected $fillable = [
        'candidate_id', 
        'doc',          
        'value',        
        'entity',       
        'reference',    
        'status',       
        'date_payment', 
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
