<?php

namespace App\Livewire\Enrollment;

use Livewire\Component;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Province;
use App\Models\ProvinceDistrict;
use App\Models\SpecialNeed;
use App\Models\PreUniversitySchool;
use App\Models\University;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TallStackUi\Traits\Interactions;

class Index extends Component
{
	use Interactions;
	
	
    public $step = 1;
	
    public $currentStep=1;
    public $total_steps=3;	
    public $surname, $name, $birthdate, $nationality, $gender, $marital_status;
    public $province_id, $province_district_id, $special_need_id;
    public $document_type, $document_number, $nuit, $cell1, $cell2, $email;
    public $pre_university_type, $pre_university_province_id, $pre_university_school_id;
    public $pre_university_year, $local_exam, $university_id, $regime, $course_id;
    public $dataConfirmed = false;
    public $candidateNumber;
    
    public $provinces, $provinceDistricts, $districts, $specialNeeds, $preUniversitySchools, $universities, $courses;
	
	public $success = 0;

    protected $rules = [
        'surname' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'birthdate' => 'required|date',
        'nationality' => 'required|string',
        'gender' => 'required|string',
        'marital_status' => 'required|string',
        'province_id' => 'required|exists:provinces,id',
        'province_district_id' => 'required|exists:province_districts,id',
        'special_need_id' => 'required|exists:special_needs,id',
        'document_type' => 'required|string',
        'document_number' => 'required|string|unique:candidates,document_number',
        'nuit' => 'nullable|string',
        'cell1' => 'required|string',
        'cell2' => 'nullable|string',
        'email' => 'nullable|email',
        'pre_university_type' => 'required|string',
        'pre_university_province_id' => 'required|exists:provinces,id',
        'pre_university_school_id' => 'required|exists:pre_university_schools,id',
        'pre_university_year' => 'required|string',
        'local_exam' => 'required|string',
        'university_id' => 'required|exists:universities,id',
        'regime' => 'required|string',
        'course_id' => 'required|exists:courses,id',
    ];

    public function mount()
    {
        $this->provinces = Province::all()->map(function ($province) {
            return ['label' => $province->name, 'value' => $province->id];
        });
		
		$this->provinceDistricts = ProvinceDistrict::all()->map(function ($provinceDistrict) {
            return ['label' => $provinceDistrict->name, 'value' => $provinceDistrict->id];
        });

        $this->specialNeeds = SpecialNeed::all()->map(function ($specialNeed) {
            return ['label' => $specialNeed->name, 'value' => $specialNeed->id];
        });

        $this->universities = University::all()->map(function ($university) {
            return ['label' => $university->name, 'value' => $university->id];
        });

        $this->courses = Course::all()->map(function ($course) {
            return ['label' => $course->name, 'value' => $course->id];
        });
		
        $this->preUniversitySchools = PreUniversitySchool::all()->map(function ($preUniversitySchool) {
            return ['label' => $preUniversitySchool->name, 'value' => $preUniversitySchool->id];
        });		

        // Initialize districts and preUniversitySchools with empty arrays
        //$this->districts = [];
        //$this->preUniversitySchools = [];
    }

   // public function updatedProvinceId($value)
   // {
   //     if ($value) {
   //         $this->districts = ProvinceDistrict::where('province_id', $value)
   //             ->get()
   //             ->map(function ($district) {
   //                 return ['label' => $district->name, 'value' => $district->id];
   //             })
   //             ->toArray();
   //     } else {
   //         $this->districts = [];
   //     }
   //     $this->province_district_id = null; // Reset the selected district
   // }
   //
   // public function updatedPreUniversityProvinceId($value)
   // {
   //     if ($value) {
   //         $this->preUniversitySchools = PreUniversitySchool::where('province_id', $value)
   //             ->get()
   //             ->map(function ($school) {
   //                 return ['label' => $school->name, 'value' => $school->id];
   //             })
   //             ->toArray();
   //     } else {
   //         $this->preUniversitySchools = [];
   //     }
   //     $this->pre_university_school_id = null; // Reset the selected school
   // }
	
	
    public function incrementSteps(){
      if($this->currentStep < $this->total_steps){
        $this->currentStep = $this->currentStep + 1;
      }
      
    }

    public function decrementSteps(){
        if($this->currentStep > 1){
            $this->currentStep = $this->currentStep - 1;
        }
        
    }

	public function setAndIncrementStep() // setAndIncrementStep($newStep)
	{
		$this->validateStep();
		$this->step = $this->step + 1;  // $this->step = $newStep;
		$this->incrementSteps();
	}	
	
	public function setAndDecrementStep()
	{
		$this->step = $this->step - 1;
		$this->decrementSteps();
	}		

    public function validateStep()
    {
        if ($this->currentStep===1) {
            $this->validate([
                'surname' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'nationality' => 'required|string',
                'gender' => 'required|string',
                'marital_status' => 'required|string',
                'province_id' => 'required|exists:provinces,id',
                //'province_district_id' => 'required|exists:province_districts,id',
                'special_need_id' => 'required|exists:special_needs,id',
                'document_type' => 'required|string',
                'document_number' => 'required|string|unique:candidates,document_number',
                'nuit' => 'nullable|string',
                'cell1' => 'required|string',
                'cell2' => 'nullable|string',
                'email' => 'nullable|email',
            ]);
        } elseif ($this->currentStep===2) {
            $this->validate([
                'pre_university_type' => 'required|string',
                'pre_university_province_id' => 'required|exists:provinces,id',
                'pre_university_school_id' => 'required|exists:pre_university_schools,id',
                'pre_university_year' => 'required|string',
                'local_exam' => 'required|string',
                'university_id' => 'required|exists:universities,id',
                'regime' => 'required|string',
                'course_id' => 'required|exists:courses,id',
            ]);
        }
    }

    public function submit()
    {
        //$this->validate();
		// Criar o candidato
		$candidate = Candidate::create([
			'user_id' 					=> null, // Temporariamente nulo, será atualizado depois
			'surname' 					=> $this->surname,
			'name' 						=> $this->name,
			'birthdate' 				=> $this->birthdate,
			'nationality' 				=> $this->nationality,
			'gender' 					=> $this->gender,
			'marital_status'			=> $this->marital_status,
			'province_id' 				=> $this->province_id,
			'province_district_id' 		=> $this->province_district_id,
			'special_need_id' 			=> $this->special_need_id,
			'document_type' 			=> $this->document_type,
			'document_number' 			=> $this->document_number,
			'nuit' 						=> $this->nuit,
			'cell1' 					=> $this->cell1,
			'cell2' 					=> $this->cell2,
			'email' 					=> $this->email,
			'pre_university_type' 		=> $this->pre_university_type,
			'pre_university_school_id' 	=> $this->pre_university_school_id,
			'pre_university_year' 		=> $this->pre_university_year,
			'local_exam' 				=> $this->local_exam,
			'university_id' 			=> $this->university_id,
			'regime' 					=> $this->regime,
			'course_id' 				=> $this->course_id,
			'status' 					=> 1, // Status inicial
		]);

		// Criar o usuário associado ao candidato
		$user = User::create([
			'name' 				=> $candidate->name . ' ' . $candidate->surname,
			//'email' 			=> $this->email,
			'username' 			=> $candidate->id, // O ID do candidato será o username
			'password' 			=> bcrypt($this->cell1), // Gerar uma senha aleatória
			'role' 				=> 'user',
		]);

		// Atualizar o campo 'user_id' no candidato com o ID do novo usuário
		$candidate->update([
			'user_id' 	=> $user->id,
		]);
		
		$this->success = true; // Move to success step
        $this->name = $candidate->name;
		$this->surname = $candidate->surname;
		$this->candidateNumber = $candidate->id; // Or generate a unique number as needed
		$this->cell1 = $candidate->cell1;
        $this->toast()->success('Successo', 'Pre-Inscrição Realizada com Sucesso.')->send();
		
        
    }

    public function render()
    {
        return view('livewire.enrollment')->layout('layouts.enroll');
    }
}
