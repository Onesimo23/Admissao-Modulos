<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function uniqueIds()
    {
        return ['uuid'];
    }
	
	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function university()
	{
		return $this->belongsTo(University::class);
	}
	
	public function province()
	{
		return $this->belongsTo(Province::class);
	}
	
	public function user()
	{
    	return $this->belongsTo(User::class);
	}
	
	public function payment()
	{
		return $this->hasOne(Payment::class)->latestOfMany();

	}	
	public function regime()
	{
		return $this->belongsTo(Regime::class);
	}
	
	public function localExam()
	{
		return $this->belongsTo(Province::class, 'local_exam');
	}
	public function getProvince()
	{
    	return Province::where('name', $this->local_exam)->first();
	}
	public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }
}
