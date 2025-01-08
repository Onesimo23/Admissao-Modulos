<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Candidate;
use App\Models\ClassModel;
use App\Models\Juri;
use App\Models\Disciplina; // Ensure that this class exists in the specified namespace

class JuriDistribution extends Component
{
    public $province_id;
    public $disciplina_id;
    public $candidates = [];
    public $salas = [];
    public $juris = [];

    public function mount()
    {
        // Inicializar com dados padrão ou estado inicial
        $this->province_id = null;
        $this->disciplina_id = null;
        $this->candidates = [];
        $this->salas = [];
        $this->juris = [];
    }

    public function updatedProvinciaId()
    {
        // Atualizar as disciplinas e candidatos com base na província
        $this->candidates = Candidate::where('province_id', $this->provincia_id)->get();
    }

    public function updatedDisciplinaId()
    {
        // Atualizar os candidatos e salas com base na disciplina
        $this->salas = ClassModel::whereHas('school', function ($query) {
            $query->where('province_id', $this->province_id);
        })->orderBy('school.prioridade')->get();

        $this->candidates = Candidate::where('province_id', $this->provincia_id)
            ->where('disciplina_id', $this->disciplina_id)
            ->get();
    }

    public function distribuir()
    {
        // Realizar a distribuição dos candidatos para as salas
        $candidates = $this->candidates;
        $salas = $this->salas;
        
        foreach ($salas as &$sala) {
            $sala['restante'] = $sala['capacidade'];
        }

        $juris = [];
        foreach ($candidates as $candidate) {
            foreach ($salas as &$sala) {
                if ($sala['restante'] > 0) {
                    $juris[] = [
                        'candidato_id' => $candidate['id'],
                        'sala_id' => $sala['id']
                    ];
                    $sala['restante']--;
                    break;
                }
            }
        }

        $this->juris = $juris;
    }

    public function saveJuris()
    {
        // Salvar as juris no banco de dados
        foreach ($this->juris as $juri) {
            Juri::create([
                'candidate_id' => $juri['candidate_id'],
                'class_model_id' => $juri['class_model_id'],
                'disciplina_id' => $this->disciplina_id,
                'data_exame' => now(), // Ajuste a data do exame conforme necessário
            ]);
        }

        session()->flash('message', 'Distribuição salva com sucesso!');
    }

    public function render()
    {
        return view('livewire.juri-distribution', [
            'provincias' => \App\Models\Province::all(),
            'disciplinas' => \App\Models\Disciplina::all(),
        ]);
    }
}
