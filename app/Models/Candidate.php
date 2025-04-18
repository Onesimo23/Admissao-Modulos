<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
	use HasFactory, HasUuids;

	protected $fillable = [
		'name',
		'surname',
		'course_id',
		'province_id',
		'local_exam',
		'regime_id',
		'email',
		'birthdate',
		'nationality',
		'gender',
		'marital_status',
		'document_type',
		'document_number',
		'nuit',
		'cell1',
		'cell2',
		'pre_university_type',
		'pre_university_year',
		'status',
		'special_need_id',
		'university_id',
		'user_id',
	];

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

	public function payments()
	{
		return $this->hasMany(Payment::class, 'candidate_id');
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

	public function juryDistributions()
	{
		return $this->hasMany(JuryDistribution::class, 'candidate_id');
	}
}
