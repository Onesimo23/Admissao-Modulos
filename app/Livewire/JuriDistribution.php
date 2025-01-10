<?php


namespace App\Livewire;

use Livewire\Component;
use App\Models\Province;
use App\Models\Disciplina;
use App\Models\Juri;
use App\Models\Candidate;
use Illuminate\Support\Facades\Log;

class JuriDistribution extends Component
{
    public $provinces = [];
    public $disciplines = [];
    public $juris = [];
    public $selectedProvince = null;
    public $selectedDiscipline = null;
    public $selectedJury = null;
    public $candidates = [];

    public function mount()
    {
        // Carregar as províncias e disciplinas
        $this->provinces = Province::all();
        $this->disciplines = Disciplina::all();
        $this->juris = collect(); // Inicializa como Collection vazia
    }

    public function updatedSelectedProvince()
    {
        // Atualizar os júris quando a província for selecionada
        $this->updateJuris();
    }

    public function updatedSelectedDiscipline()
    {
        // Atualizar os júris quando a disciplina for selecionada
        $this->updateJuris();
    }

    public function updatedSelectedJury()
    {
        // Quando um júri for selecionado, carregar os candidatos
        $this->loadCandidates();
    }

    protected function updateJuris()
    {
        if ($this->selectedProvince && $this->selectedDiscipline) {
            // Adicione um log para debug
            Log::info('Buscando júris:', [
                'provincia' => $this->selectedProvince,
                'disciplina' => $this->selectedDiscipline
            ]);

            $this->juris = Juri::with('school')
                ->whereHas('school', function ($query) {
                    $query->where('province_id', $this->selectedProvince);
                })
                ->where('disciplina_id', $this->selectedDiscipline)
                ->get();

            // Log do resultado
        } else {
            $this->juris = collect();
        }

        // Resetar o júri selecionado quando mudar província ou disciplina
        $this->selectedJury = null;
    }

    protected function loadCandidates()
    {
        if ($this->selectedJury) {
            // Busca os candidatos através da tabela de distribuição
            $this->candidates = Candidate::whereHas('juryDistributions', function ($query) {
                $query->where('juri_id', $this->selectedJury)
                    ->where('disciplina_id', $this->selectedDiscipline);
            })->get();
        } else {
            $this->candidates = collect();
        }
    }

    public function store()
    {
        // Validação com mensagens em português
        $this->validate([
            'selectedProvince' => 'required|numeric',
            'selectedDiscipline' => 'required|numeric',
            'selectedJury' => 'required|numeric'
        ], [
            'selectedProvince.required' => 'Por favor, selecione uma província',
            'selectedDiscipline.required' => 'Por favor, selecione uma disciplina',
            'selectedJury.required' => 'Por favor, selecione um júri'
        ]);

        try {
            // Lógica de salvamento aqui
            session()->flash('message', 'Distribuição salva com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar a distribuição.');
        }
    }


    public function render()
    {
        return view('livewire.juri-distribution');
    }
}
