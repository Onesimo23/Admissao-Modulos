<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Province;
use App\Models\SpecialNeed;
use App\Models\Course;
use App\Models\University;
use App\Models\Regime;
use Illuminate\Support\Facades\Auth;
use TallStackUi\Traits\Interactions;
use Carbon\Carbon;

class CandidateDashboard extends Component
{
    use Interactions;

    // Form Data Properties
    public $candidate;
	
    public $surname;
    public $name;
    public $birthdate;
    public $nationality;
    public $gender;
    public $marital_status;
    public $province_id;
    public $special_need_id;
    public $document_type;
    public $document_number;
    public $nuit;
    public $cell1;
    public $cell2;
    public $email;
    
    // Academic Data Properties
    public $pre_university_type;
    public $pre_university_year;
    public $university_id;
    public $regime_id;
    public $course_id;
    public $local_exam;
    
    // Component State
    public $currentStep = 1;
    public $total_steps = 2;
    
    // Collections para os selects
    public $provinces = [];
    public $specialNeeds = [];
    public $availableRegimes = [];
    public $availableCourses = [];
    public $showLocalExam = false;
    public $universities = [];
	
    public $nationalityOptions 				= [];
    public $genderOptions 					= [];
    public $maritalStatusOptions 			= [];
    public $documentTypeOptions 			= [];
	public $preUniversityTypeOptions		= [];	
	
	public $success 	= 0;
	public $editForm;
	public $detailsForm;

    protected function rules()
    {
        $candidateId = $this->candidate->id;
        
        return [
            'surname' 				=> 'required|string|max:255',
            'name' 					=> 'required|string|max:255',
            'birthdate' 			=> 'required|date',
            'nationality' 			=> 'required|string',
            'gender' 				=> 'required|string',
            'marital_status' 		=> 'required|string',
            'province_id' 			=> 'required|exists:provinces,id',
            'special_need_id' 		=> 'required|exists:special_needs,id',
            'document_type' 		=> 'required|string',
            'document_number' 		=> 'required|string|unique:candidates,document_number,' . $candidateId,
            'nuit' 					=> 'nullable|string|unique:candidates,nuit,' . $candidateId,
            'cell1' 				=> 'required|numeric|digits:9',
            'cell2' 				=> 'nullable|numeric|digits:9',
            'email' 				=> 'nullable|email',
            'pre_university_type' 	=> 'required|string',
            'pre_university_year' 	=> 'required|numeric|digits:4',
            'local_exam' 			=> 'nullable|required_if:regime_id,1',
            'university_id' 		=> 'required|exists:universities,id',
            'regime_id' 			=> 'required|exists:regimes,id',
            'course_id' 			=> 'required|exists:courses,id',
        ];
    }

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
        $this->candidate = Auth::user()->candidate;
		//$this->candidate = Candidate::where('user_id', Auth::id())->firstOrFail();
        $this->candidate = Auth::user()->candidate->load([
            'course',
            'province',
            'juryDistributions.jury.room.school',
            'juryDistributions.disciplina'
        ]);
        
		$this->currentStep 		= 1; 	// Inicia na primeira etapa
        $this->availableCourses = []; 	// Inicializa como array vazio
        
		// Carregar dados do candidato
        $this->loadCandidateData();		

        // Definir as opções dos selects estáticos
        $this->nationalityOptions = [
            ['label' => 'Moçambicana', 'value' => 'MOçAMBICANA'],
            ['label' => 'Estrangeira', 'value' => 'ESTRANGEIRA']
        ];
        
        $this->genderOptions = [
            ['label' => 'Masculino', 'value' => 'MASCULINO'],
            ['label' => 'Feminino', 'value' => 'FEMININO']
        ];
        
        $this->maritalStatusOptions = [
            ['label' => 'Solteiro(a)', 'value' => 'SOLTEIRO(A)'],
            ['label' => 'Casado(a)', 'value' => 'CASADO(A)'],
            ['label' => 'Viuvo(a)', 'value' => 'VIUVO(A)'],
            ['label' => 'Divorciado(a)', 'value' => 'DIVORCIADO(A)']
        ];
        
        $this->documentTypeOptions = [
            ['label' => 'BI', 'value' => 'BI'],
            ['label' => 'Passaporte', 'value' => 'PASSAPORTE']
        ];
		
		$preUniversityTypeOptions = [
			['label' => '12ª - Grupo A (ou equivalente)', 'value' => '12ª - GRUPO A'],
			['label' => '12ª - Grupo B (ou equivalente)', 'value' => '12ª - GRUPO B'],
			['label' => '12ª - Grupo C (ou equivalente)', 'value' => '12ª - GRUPO C'],
		];	

        $this->provinces = Province::orderBy('name')->get()->map(function ($province) {
            return ['label' => $province->name, 'value' => $province->id];
        });

        $this->specialNeeds = SpecialNeed::all()->map(function ($specialNeed) {
            return ['label' => $specialNeed->name, 'value' => $specialNeed->id];
        });
		
        $this->universities = University::orderBy('name')->get()->map(function ($university) {
            return ['label' => $university->name, 'value' => $university->id];
        });		
        

        
        // Se já houver university_id e regime_id, carrega os cursos disponíveis
        if ($this->university_id && $this->regime_id) {
            $this->updateAvailableCourses();
        }
    }

    public function loadCandidateData()
    {
        if ($this->candidate) {
            $this->surname 				= $this->candidate->surname;
            $this->name 				= $this->candidate->name;
            $this->birthdate 			= $this->candidate->birthdate;
            $this->nationality 			= $this->candidate->nationality;
            $this->gender 				= $this->candidate->gender;
            $this->marital_status 		= $this->candidate->marital_status;
            $this->province_id 			= $this->candidate->province_id;
            $this->special_need_id 		= $this->candidate->special_need_id;
            $this->document_type 		= $this->candidate->document_type;
            $this->document_number 		= $this->candidate->document_number;
            $this->nuit 				= $this->candidate->nuit;
            $this->cell1 				= $this->candidate->cell1;
            $this->cell2 				= $this->candidate->cell2;
            $this->email 				= $this->candidate->email;
            $this->pre_university_year 	= $this->candidate->pre_university_year;
        
        }
    }

    public function updatedUniversityId($value)
    {
        // Reseta regime, curso e limpa as opções
        $this->regime_id = '';
        $this->course_id = '';
        $this->availableRegimes = [];
        $this->availableCourses = [];
        $this->showLocalExam = $this->regime_id == 1;

        if ($value) {
            // Buscar regimes disponíveis para esta universidade
            $this->availableRegimes = Regime::whereHas('universityCourses', function ($query) use ($value) {
                $query->where('university_id', $value);
            })
            ->orderBy('name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
        }
    }

    public function updatedRegimeId($value)
    {
        // Reseta o curso
        $this->reset(['course_id']);
        $this->updateAvailableCourses(); 
        $this->showLocalExam = $value == 1;
    }

    public function updateAvailableCourses()
    {
        if ($this->university_id && $this->regime_id) {
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

    public function setAndIncrementStep()
    {
        $this->validateStep();
        if ($this->currentStep < $this->total_steps) {
            $this->currentStep++;
        }
    }

    public function setAndDecrementStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function validateStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'surname' 			=> 'required|string|max:255',
                'name' 				=> 'required|string|max:255',
                'birthdate' 		=> 'required|date',
                'nationality' 		=> 'required|string',
                'gender' 			=> 'required|string',
                'marital_status' 	=> 'required|string',
                'province_id' 		=> 'required|exists:provinces,id',
                'special_need_id' 	=> 'required|exists:special_needs,id',
                'document_type' 	=> 'required|string',
                'document_number' 	=> 'required|string',
                'nuit' 				=> 'required|string',
                'cell1' 			=> 'required|string',
                'cell2' 			=> 'nullable|string',
                'email' 			=> 'nullable|email',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'pre_university_type' 	=> 'required|string',
                'pre_university_year' 	=> 'required|numeric|digits:4',
                'university_id' 		=> 'required|exists:universities,id',
                'regime_id' 			=> 'required|exists:regimes,id',
                'course_id' 			=> 'required|exists:courses,id',
                'local_exam' 			=> 'nullable|required_if:regime_id,1',
            ]);
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            
            if ($this->regime_id != 1) {
                $this->local_exam = null;
            }
    
            $this->candidate->update([
                'surname' 				=> strtoupper($this->surname),
                'name' 					=> strtoupper($this->name),
                'birthdate' 			=> $this->birthdate,
                'nationality' 			=> strtoupper($this->nationality),
                'gender' 				=> strtoupper($this->gender),
                'marital_status' 		=> strtoupper($this->marital_status),
                'province_id' 			=> $this->province_id,
                'special_need_id' 		=> $this->special_need_id,
                'document_type' 		=> strtoupper($this->document_type),
                'document_number' 		=> strtoupper($this->document_number),
                'nuit' 					=> strtoupper($this->nuit),
                'cell1' 				=> trim($this->cell1),
                'cell2' 				=> trim($this->cell2),
                'email' 				=> $this->email,
                'pre_university_type' 	=> strtoupper($this->pre_university_type),
                'pre_university_year' 	=> $this->pre_university_year,
                'university_id' 		=> $this->university_id,
                'regime_id' 			=> $this->regime_id,
                'course_id' 			=> $this->course_id,
                'local_exam' 			=> $this->local_exam,
            ]);
			
			// Atualiza também o nome do usuário
			User::where('id', Auth::id())->update([
				'name' 				=> $this->name . ' ' . $this->surname,
				'document_number' 	=> $this->document_number,
			]);			

			$this->success 			= true;
			
            $this->toast()->success('Successo', 'Dados da candidatura atualizados com sucesso,')->send();
            $this->dispatch('close-slide');
        } catch (\Exception $e) {
            $this->toast()->error('Erro', 'Erro ao atualizar a candidatura. Por favor, tente novamente.')->send();
        }
    }

    public function render()
    {
        $query3 = University::orderBy('name')->pluck('name', 'id');
        $query4 = $this->availableRegimes;
        $query5 = $this->availableCourses;
		
		$nationalityOptions1 			= $this->nationalityOptions;
		$genderOptions1 				= $this->genderOptions;
		$maritalStatusOptions1 			= $this->maritalStatusOptions;
		$documentTypeOptions1 			= $this->documentTypeOptions;
		$preUniversityTypeOptions1		= $this->preUniversityTypeOptions;

        return view('livewire.dashboard', [
            'query3' => $query3,
            'query4' => $query4,
            'query5' => $query5,
            'nationalityOptions' 			=> $nationalityOptions1,
            'genderOptions' 				=> $genderOptions1,
            'maritalStatusOptions' 			=> $maritalStatusOptions1,
            'documentTypeOptions' 			=> $documentTypeOptions1,	
			'preUniversityTypeOptions' 		=> $preUniversityTypeOptions1,
        ]);
    }
}