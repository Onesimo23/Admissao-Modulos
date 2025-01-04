<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Candidate;
use App\Models\Course;
use App\Models\Province;
use App\Models\Regime;
use App\Models\SpecialNeed;
use App\Models\University;
use Illuminate\Support\Facades\Auth;
use TallStackUi\Traits\Interactions;


use Carbon\Carbon;

class CandidateDashboard extends Component
{
    use Interactions;

    // Form Data Properties
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
    public $candidate;
    
    // Collections para os selects
    public $provinces = [];
    public $specialNeeds = [];
    public $availableRegimes = [];
    public $availableCourses = [];
    public $showLocalExam = false;

    protected $rules = [
        'surname' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'birthdate' => 'required|date',
        'nationality' => 'required|string',
        'gender' => 'required|string',
        'marital_status' => 'required|string',
        'province_id' => 'required|exists:provinces,id',
        'special_need_id' => 'required|exists:special_needs,id',
        'document_type' => 'required|string',
        'document_number' => 'required|string|max:20',
        'nuit' => 'required|string|max:15',
        'cell1' => 'required|string|max:15',
        'cell2' => 'nullable|string|max:15',
        'email' => 'nullable|email|max:255',
        'pre_university_type' => 'required|string',
        'pre_university_year' => 'required|integer|min:1900|max:2024',
        'university_id' => 'required|exists:universities,id',
        'regime_id' => 'required|exists:regimes,id',
        'course_id' => 'required|exists:courses,id',
        'local_exam' => 'required_if:regime_id,1'
    ];

    public function mount()
    {
        $this->currentStep = 1; // Inicia na primeira etapa
        $this->availableCourses = []; // Inicializa como array vazio
        $this->candidate = Auth::user()->candidate;
        
        // Carregar as coleções para os selects
        $this->provinces = Province::all()->map(function ($province) {
            return ['label' => $province->name, 'value' => $province->id];
        });

        $this->specialNeeds = SpecialNeed::all()->map(function ($specialNeed) {
            return ['label' => $specialNeed->name, 'value' => $specialNeed->id];
        });
        
        // Carregar dados do candidato
        $this->loadCandidateData();
        
        // Se já houver university_id e regime_id, carrega os cursos disponíveis
        if ($this->university_id && $this->regime_id) {
            $this->updateAvailableCourses();
        }
    }

    public function loadCandidateData()
    {
        if ($this->candidate) {
            $this->surname = $this->candidate->surname;
            $this->name = $this->candidate->name;
            $this->birthdate = $this->candidate->birthdate;
            $this->nationality = $this->candidate->nationality;
            $this->gender = $this->candidate->gender;
            $this->marital_status = $this->candidate->marital_status;
            $this->province_id = $this->candidate->province_id;
            $this->special_need_id = $this->candidate->special_need_id;
            $this->document_type = $this->candidate->document_type;
            $this->document_number = $this->candidate->document_number;
            $this->nuit = $this->candidate->nuit;
            $this->cell1 = $this->candidate->cell1;
            $this->cell2 = $this->candidate->cell2;
            $this->email = $this->candidate->email;
            $this->pre_university_type = $this->candidate->pre_university_type;
            $this->pre_university_year = $this->candidate->pre_university_year;
            $this->university_id = $this->candidate->university_id;
            $this->regime_id = $this->candidate->regime_id;
            $this->course_id = $this->candidate->course_id;
            $this->local_exam = $this->candidate->local_exam;
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
                'surname' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'nationality' => 'required|string',
                'gender' => 'required|string',
                'marital_status' => 'required|string',
                'province_id' => 'required|exists:provinces,id',
                'special_need_id' => 'required|exists:special_needs,id',
                'document_type' => 'required|string',
                'document_number' => 'required|string',
                'nuit' => 'required|string',
                'cell1' => 'required|string',
                'cell2' => 'nullable|string',
                'email' => 'nullable|email',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'pre_university_type' => 'required|string',
                'pre_university_year' => 'required|numeric|digits:4',
                'university_id' => 'required|exists:universities,id',
                'regime_id' => 'required|exists:regimes,id',
                'course_id' => 'required|exists:courses,id',
                'local_exam' => 'nullable|required_if:regime_id,1',
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
                'surname' => strtoupper($this->surname),
                'name' => strtoupper($this->name),
                'birthdate' => $this->birthdate,
                'nationality' => strtoupper($this->nationality),
                'gender' => strtoupper($this->gender),
                'marital_status' => strtoupper($this->marital_status),
                'province_id' => $this->province_id,
                'special_need_id' => $this->special_need_id,
                'document_type' => strtoupper($this->document_type),
                'document_number' => strtoupper($this->document_number),
                'nuit' => strtoupper($this->nuit),
                'cell1' => trim($this->cell1),
                'cell2' => trim($this->cell2),
                'email' => $this->email,
                'pre_university_type' => strtoupper($this->pre_university_type),
                'pre_university_year' => $this->pre_university_year,
                'university_id' => $this->university_id,
                'regime_id' => $this->regime_id,
                'course_id' => $this->course_id,
                'local_exam' => $this->local_exam,
            ]);

            $this->toast()->success('Successo', 'Candidatura atualizada com sucesso!')->send();
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

        return view('livewire.dashboard', [
            'query3' => $query3,
            'query4' => $query4,
            'query5' => $query5,
        ]);
    }
}