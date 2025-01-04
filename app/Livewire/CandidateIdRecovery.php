<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Candidate;
use TallStackUi\Traits\Interactions;

class CandidateIdRecovery extends Component
{
    use Interactions;

    public $document_or_nuit = '';
    public $candidate_id = null;
    public $isProcessing = false; // Nova variável para controlar o estado do botão

    protected $rules = [
        'document_or_nuit' => 'required|string',
    ];

    public function recoverCandidateId()
    {
        $this->validate();

        $this->isProcessing = true; // Define o estado como "Processando"

        try {
            // Busca o candidato usando o valor fornecido
            $candidate = Candidate::where('document_number', $this->document_or_nuit)
                ->orWhere('nuit', $this->document_or_nuit)
                ->first();

            if ($candidate) {
                $this->candidate_id = $candidate->id;
                $this->toast()
                    ->success('Sucesso!', 'Número de candidato recuperado: ' . $this->candidate_id)
                    ->send();
            } else {
                $this->toast()
                    ->error(
                        'Erro',
                        'Nenhum candidato foi encontrado com o número fornecido.'
                    )
                    ->send();
                $this->candidate_id = null;
            }
        } catch (\Exception $e) {
            $this->toast()
                ->error('Erro', 'Ocorreu um erro ao processar sua solicitação.')
                ->send();
        } finally {
            $this->isProcessing = false; // Redefine o estado após a verificação
        }
    }

    public function render()
    {
        return view('livewire.candidate-id-recovery')
            ->extends('layouts.recover')
            ->section('content');
    }
}
