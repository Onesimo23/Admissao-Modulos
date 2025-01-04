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
use App\Models\UniversityCourse;
use App\Models\Regime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TallStackUi\Traits\Interactions;
use Carbon\Carbon;

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
    public $pre_university_year, $local_exam, $university_id, $regime_id, $course_id;
    public $dataConfirmed = false;
    public $candidateNumber;
    
    public $provinces, $provinceDistricts, $districts, $specialNeeds, $preUniversitySchools, $universities, $courses, $regimes;
	
	// Variáveis adicionais para armazenar os nomes
    //public $province_name, $special_need_name, $university_name, $regime_name, $course_name, $local_exam_name;
	
	public $availableCourses = [];
	public $availableRegimes = [];
	
	public $success = 0;

    protected $rules = [
        'surname' 					=> 'required|string|max:255',
        'name' 						=> 'required|string|max:255',
        'birthdate' 				=> 'required|date',
        'nationality' 				=> 'required|string',
        'gender' 					=> 'required|string',
        'marital_status' 			=> 'required|string',
        'province_id'	 			=> 'required|exists:provinces,id',
        //'province_district_id' 	=> 'required|exists:province_districts,id',
        'special_need_id' 			=> 'required|exists:special_needs,id',
        'document_type' 			=> 'required|string',
        'document_number' 			=> 'required|string|unique:candidates,document_number',
        'nuit' 						=> 'nullable|string|unique:candidates,nuit',
        'cell1' 					=> 'required|numeric|digits:9',
        'cell2' 					=> 'nullable|numeric|digits:9',
        'email' 					=> 'nullable|email',
        'pre_university_type' 		=> 'required|string',
        //'pre_university_province_id' 	=> 'required|exists:provinces,id',
        //'pre_university_school_id' 	=> 'required|exists:pre_university_schools,id',
        'pre_university_year' 		=> 'required|numeric|digits:4',
        'local_exam'                => 'nullable|required_if:regime_id,1', // Requerido se regime_id = 1, mas pode ser nulo
        'university_id' 			=> 'required|exists:universities,id',
        'regime_id' 				=> 'required|exists:regimes,id',
        'course_id' 				=> 'required|exists:courses,id',
    ];

	public function messages()
	{
		return [
			'surname.required'                => 'O Apelido é obrigatório.',
			'surname.string'                  => 'O Apelido deve ser uma string.',
			'surname.max'                     => 'O Apelido não pode ter mais de 255 caracteres.',
			'name.required'                   => 'O nome é obrigatório.',
			'name.string'                     => 'O nome deve ser uma string.',
			'name.max'                        => 'O nome não pode ter mais de 255 caracteres.',
			'birthdate.required'              => 'A data de nascimento é obrigatória.',
			'birthdate.date'                  => 'A data de nascimento deve ser uma data válida.',
			'nationality.required'            => 'A nacionalidade é obrigatória.',
			'nationality.string'              => 'A nacionalidade deve ser uma string.',
			'gender.required'                 => 'O gênero é obrigatório.',
			'gender.string'                   => 'O gênero deve ser uma string.',
			'marital_status.required'         => 'O estado civil é obrigatório.',
			'marital_status.string'           => 'O estado civil deve ser uma string.',
			'province_id.required'            => 'A província é obrigatória.',
			'province_id.exists'              => 'A província selecionada é inválida.',
			//'province_district_id.required' => 'O distrito provincial é obrigatório.',
			//'province_district_id.exists'   => 'O distrito provincial selecionado é inválido.',
			'special_need_id.required'        => 'O tipo de necessidade especial é obrigatório.',
			'special_need_id.exists'          => 'O tipo de necessidade especial selecionado é inválido.',
			'document_type.required'          => 'O tipo de documento é obrigatório.',
			'document_type.string'            => 'O tipo de documento deve ser uma string.',
			'document_number.required'        => 'O número do documento é obrigatório.',
			'document_number.string'          => 'O número do documento deve ser uma string.',
			'document_number.unique'          => 'Este número de documento já está em uso.',
			'nuit.nullable'                   => 'O NUIT é opcional.',
			'nuit.string'                     => 'O NUIT deve ser uma string.',
			'nuit.unique'                     => 'Este NUIT já está em uso.',
			'cell1.required'                  => 'O número de celular é obrigatório.',
			'cell1.numeric'                   => 'O número de celular deve ser numérico.',
			'cell1.digits'                    => 'O número de celular deve ter exatamente 9 dígitos.',
			'cell2.nullable'                  => 'O segundo número de celular é opcional.',
			'cell2.numeric'                   => 'O segundo número de celular deve ser numérico.',
			'cell2.digits'                    => 'O segundo número de celular deve ter exatamente 9 dígitos.',
			'email.nullable'                  => 'O email é opcional.',
			'email.email'                     => 'O email deve ser um endereço de email válido.',
			'pre_university_type.required'    => 'O tipo de pré-universitário é obrigatório.',
			'pre_university_type.string'      => 'O tipo de pré-universitário deve ser uma string.',
			//'pre_university_province_id.required' => 'A província do pré-universitário é obrigatória.',
			//'pre_university_province_id.exists'   => 'A província do pré-universitário selecionada é inválida.',
			//'pre_university_school_id.required' => 'A escola pré-universitária é obrigatória.',
			//'pre_university_school_id.exists'   => 'A escola pré-universitária selecionada é inválida.',
			'pre_university_year.required'    => 'O ano do pré-universitário é obrigatório.',
			'pre_university_year.numeric'     => 'O ano do pré-universitário deve ser numérico.',
			'pre_university_year.digits'      => 'O ano do pré-universitário deve ter exatamente 4 dígitos.',
			'local_exam.nullable'             => 'O local do exame é opcional.',
			'local_exam.required_if'          => 'O local do exame é obrigatório se o regime for 1.',
			'university_id.required'          => 'A universidade é obrigatória.',
			'university_id.exists'            => 'A universidade selecionada é inválida.',
			'regime_id.required'              => 'O regime é obrigatório.',
			'regime_id.exists'                => 'O regime selecionado é inválido.',
			'course_id.required'              => 'O curso é obrigatório.',
			'course_id.exists'                => 'O curso selecionado é inválido.',
		];
	}	
		
    public function mount()
    {

		$this->provinces = Province::orderBy('name')->get()->map(function ($province) {
            return ['label' => $province->name, 'value' => $province->id];
        });
		
		//$this->provinceDistricts = ProvinceDistrict::all()->map(function ($provinceDistrict) {
        //    return ['label' => $provinceDistrict->name, 'value' => $provinceDistrict->id];
        //});

        $this->specialNeeds = SpecialNeed::all()->map(function ($specialNeed) {
            return ['label' => $specialNeed->name, 'value' => $specialNeed->id];
        });
		

        //$this->universities = University::all()->map(function ($university) {
        //    return ['label' => $university->name, 'value' => $university->id];
        //});

        //$this->courses = Course::all()->map(function ($course) {
        //    return ['label' => $course->name, 'value' => $course->id];
        //});
		
        //$this->regimes = Regime::all()->map(function ($regime) {
        //    return ['label' => $regime->name, 'value' => $regime->id];
        //});

        // Initialize courses as empty until university and regime are selected
        //$this->availableCourses = [];
        //$this->availableRegimes = [];		
		
        //$this->preUniversitySchools = PreUniversitySchool::all()->map(function ($preUniversitySchool) {
        //    return ['label' => $preUniversitySchool->name, 'value' => $preUniversitySchool->id];
        //});		

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



	public function updatedUniversityId($value)
	{
		// Reseta regime, curso e limpa as opções de cursos disponíveis
		//$this->reset(['regime_id', 'course_id', 'availableCourses']);
		
        $this->regime_id = '';
		$this->course_id = '';
		$this->availableRegimes = [];
		$this->availableCourses = [];

		if ($value) {
			// Buscar regimes disponíveis para esta universidade
			$this->availableRegimes = Regime::where('id', '>', 1)->whereHas('universityCourses', function ($query) use ($value) {
				$query->where('university_id', $value);
			})
			->orderBy('name')
			->get()
			->pluck('name', 'id')
			//->map(function ($regime) {
			//	return [
			//		'label' => $regime->name,
			//		'value' => $regime->id
			//	];
			//})
			->toArray();
			//dd($this->availableRegimes);
		} else {
			$this->availableRegimes = [];
		}
	}

	public function updatedRegimeId($value)
	{
		// Reseta o curso e limpa as opções de cursos disponíveis
		$this->reset(['course_id']);
		$this->updateAvailableCourses();
	}

	private function updateAvailableCourses()
	{
		if ($this->university_id && $this->regime_id) {
			// Filtra os cursos disponíveis com base na universidade e regime selecionados
			$this->availableCourses = Course::whereHas('universityCourses', function ($query) {
				$query->where('university_id', $this->university_id)
					  ->where('regime_id', $this->regime_id);
			})
			->orderBy('name')
			->get()
			->map(function ($course) {
				return [
					'label' => $course->name,
					'value' => $course->id
				];
			})
			->toArray();
		} else {
			$this->availableCourses = [];
		}
	}

	public function getShowLocalExamProperty()
	{
		// Exibe o local do exame se o regime selecionado for o regime 1
		return $this->regime_id == 1;
	}
	
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
				'surname' 					=> 'required|string|max:255',
				'name' 						=> 'required|string|max:255',
				'birthdate' 				=> 'required|date',
				'nationality' 				=> 'required|string',
				'gender' 					=> 'required|string',
				'marital_status' 			=> 'required|string',
				'province_id'	 			=> 'required|exists:provinces,id',
				//'province_district_id' 	=> 'required|exists:province_districts,id',
				'special_need_id' 			=> 'required|exists:special_needs,id',
				'document_type' 			=> 'required|string',
				'document_number' 			=> 'required|string|unique:candidates,document_number',
				'nuit' 						=> 'nullable|string|unique:candidates,nuit',
				'cell1' 					=> 'required|numeric|digits:9',
				'cell2' 					=> 'nullable|numeric|digits:9',
				'email' 					=> 'nullable|email',
            ]);
        } elseif ($this->currentStep===2) {
            $this->validate([
				'pre_university_type' 		=> 'required|string',
				//'pre_university_province_id' 	=> 'required|exists:provinces,id',
				//'pre_university_school_id' 	=> 'required|exists:pre_university_schools,id',
				'pre_university_year' 		=> 'required|numeric|digits:4',
				'local_exam'                => 'nullable|required_if:regime_id,1', // Requerido se regime_id = 1, mas pode ser nulo
				'university_id' 			=> 'required|exists:universities,id',
				'regime_id' 				=> 'required|exists:regimes,id',
				'course_id' 				=> 'required|exists:courses,id',
            ]);
        }
    }

    public function submit()
    {
        $this->validate();
		// Criar o candidato
		$candidate = Candidate::create([
			'user_id' 					=> null, // Temporariamente nulo, será atualizado depois
			'surname'                	=> strtoupper($this->surname),
			'name'                   	=> strtoupper($this->name),
			'birthdate' 				=> $this->birthdate,
			'nationality'            	=> strtoupper($this->nationality),
			'gender'                 	=> strtoupper($this->gender),
			'marital_status'         	=> strtoupper($this->marital_status),
			'province_id' 				=> $this->province_id,
			//'province_district_id' 		=> $this->province_district_id,
			'special_need_id' 			=> $this->special_need_id,
			'document_type'          	=> strtoupper($this->document_type),
			'document_number'        	=> strtoupper($this->document_number),
			'nuit'                   	=> strtoupper($this->nuit),
			'cell1' 					=> trim($this->cell1),
			'cell2' 					=> trim($this->cell2),
			'email' 					=> $this->email,
			'pre_university_type'    	=> strtoupper($this->pre_university_type),
			//'pre_university_school_id' 	=> $this->pre_university_school_id,
			'pre_university_year' 		=> $this->pre_university_year,
			'local_exam' 				=> $this->local_exam,
			'university_id' 			=> $this->university_id,
			'regime_id' 				=> $this->regime_id,
			'course_id' 				=> $this->course_id,
			'status' 					=> 1, // Status inicial
		]);

		// Criar o usuário associado ao candidato
		$user = User::create([
			'name' 				=> $candidate->name . ' ' . $candidate->surname,
			'document_number' 	=> $this->document_number,
			'username' 			=> $candidate->id, 				
			'password' 			=> bcrypt(trim($this->cell1)), 
			'role' 				=> 'user',
		]);

		// Atualizar o campo 'user_id' no candidato com o ID do novo usuário
		$candidate->update([
			'user_id' 			=> $user->id,
		]);
		
		$this->success 			= true; // Move to success step
        $this->name 			= $candidate->name;
		$this->surname 			= $candidate->surname;
		$this->candidateNumber 	= $candidate->id;
		$this->cell1 			= $candidate->cell1;
		
        $this->toast()->success('Successo', 'Pre-Inscrição Realizada com Sucesso.')->send();
		
        
    }

    public function render()
    {
		
		$query3 = University::orderBy('name')->pluck('name', 'id');
		$query4 = $this->availableRegimes;
		$query5 = $this->availableCourses;	
		
		// Condicional para executar apenas se $currentStep for 3
		if ($this->currentStep === 3) 
		{
			// Format names from database queries with fallback to 'N/A'
			$province_name 		= Province::whereId($this->province_id)->value('name') ?? 'N/A';
			$special_need_name 	= SpecialNeed::whereId($this->special_need_id)->value('name') ?? 'N/A';
			$university_name 	= University::whereId($this->university_id)->value('name') ?? 'N/A';
			$regime_name 		= Regime::whereId($this->regime_id)->value('name') ?? 'N/A';
			$course_name 		= Course::whereId($this->course_id)->value('name') ?? 'N/A';
			$local_exam_name 	= Province::whereId($this->local_exam)->value('name') ?? 'N/A';
			
			//dd($university_name);

			// Format date to Brazilian Portuguese format
			$birthdate_pt = $this->birthdate ? Carbon::parse($this->birthdate)->format('d/m/Y') : 'N/A';

			// Format document type name if it's stored as an ID
			//$document_type_name = match($this->document_type) {
			//	'bi' => 'Bilhete de Identidade',
			//	'passport' => 'Passaporte',
			//	'dire' => 'DIRE',
			//	default => $this->document_type ?? 'N/A'
			//};
            //
			//// Format gender if it's stored as a single letter or code
			//$gender_name = match(strtolower($this->gender)) {
			//	'm' => 'Masculino',
			//	'f' => 'Feminino',
			//	default => $this->gender ?? 'N/A'
			//};
            //
			//// Format marital status if it's stored as a code
			//$marital_status_name = match(strtolower($this->marital_status)) {
			//	'single' => 'Solteiro(a)',
			//	'married' => 'Casado(a)',
			//	'divorced' => 'Divorciado(a)',
			//	'widowed' => 'Viúvo(a)',
			//	default => $this->marital_status ?? 'N/A'
			//};
            //
			//// Format pre-university type if it's stored as a code
			//$pre_university_type_name = match(strtolower($this->pre_university_type)) {
			//	'general' => 'Ensino Geral',
			//	'technical' => 'Ensino Técnico',
			//	'professional' => 'Ensino Profissional',
			//	default => $this->pre_university_type ?? 'N/A'
			//};

			return view('livewire.enrollment', compact(
				'query3',
				'query4',
				'query5',
				'province_name',
				'special_need_name',
				'university_name',
				'regime_name',
				'course_name',
				'local_exam_name',
				'birthdate_pt',
				//'document_type_name',
				//'gender_name',
				//'marital_status_name',
				//'pre_university_type_name'
			))->layout('layouts.enroll');
		} else {

			return view('livewire.enrollment', compact(
				'query3',
				'query4',
				'query5',
			))->layout('layouts.enroll');
		}			
    }
}
