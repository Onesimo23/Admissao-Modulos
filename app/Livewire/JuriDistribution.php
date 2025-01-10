<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Province;
use App\Models\Disciplina;
use App\Models\Juri;
use App\Models\Candidate;
use Illuminate\Support\Collection;
use App\Services\JuryDistributionService;
use Illuminate\Support\Facades\Log;

class JuriDistribution extends Component
{
    public $selectedProvince = null;
    public $selectedDiscipline = null;
    public $selectedJury = null;
    public $provinces;
    public $juris;
    public $candidates;
    public $allDisciplines = [];

    public function mount()
    {
        $this->provinces = Province::all();
        $this->loadAllDisciplines();
        $this->juris = collect();
        $this->candidates = collect();
    }

    private function loadAllDisciplines()
    {
        // Carrega todas as disciplinas únicas (disciplina1 e disciplina2)
        $disciplinas = Disciplina::all();
        $allDisciplines = new Collection();

        foreach ($disciplinas as $disciplina) {
            $allDisciplines->push([
                'label' => $disciplina->disciplina1,
                'value' => $disciplina->disciplina1
            ]);
            $allDisciplines->push([
                'label' => $disciplina->disciplina2,
                'value' => $disciplina->disciplina2
            ]);
        }

        // Remove duplicatas e ordena
        $this->allDisciplines = $allDisciplines->unique('value')
            ->sortBy('label')
            ->values()
            ->toArray();
    }

    public function updatedSelectedProvince($value)
    {
        $this->selectedJury = null;
        $this->updateJurisList();
    }

    public function updatedSelectedDiscipline($value)
    {
        $this->selectedJury = null;
        $this->updateJurisList();
    }

    private function updateJurisList()
    {
        if ($this->selectedProvince && $this->selectedDiscipline) {
            // Busca júris baseado no nome da disciplina e província
            $this->juris = Juri::query()
                ->where('name', 'LIKE', '%' . $this->selectedDiscipline . '%')
                ->whereHas('room.school', function ($query) {
                    $query->where('province_id', $this->selectedProvince);
                })
                ->get();

            // Debug
            Log::info('Query Júris', [
                'província' => $this->selectedProvince,
                'disciplina' => $this->selectedDiscipline,
                'quantidade_juris' => $this->juris->count()
            ]);
        } else {
            $this->juris = collect();
        }
    }

    public function updatedSelectedJury($value)
    {
        if ($value) {
            $this->loadCandidates();
        } else {
            $this->candidates = collect();
        }
    }

    private function loadCandidates()
    {
        if ($this->selectedJury) {
            $this->candidates = Candidate::whereHas('juryDistributions', function ($query) {
                $query->where('juri_id', $this->selectedJury);
            })->get();
        }
    }

    public function distribute()
    {
        $distributionService = new JuryDistributionService();
        $distributionService->distribute();
        
        session()->flash('message', 'Distribuição de júris realizada com sucesso!');
    }

    public function render()
    {
        return view('livewire.juri-distribution');
    }
}