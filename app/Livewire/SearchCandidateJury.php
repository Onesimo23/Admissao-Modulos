<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Candidate;
use TallStackUi\Traits\Interactions;

class SearchCandidateJury extends Component
{
    use Interactions;

    public $candidateNumber = '';
    public $candidateData = null;
    public $isProcessing = false;

    protected $rules = [
        'candidateNumber' => 'required|integer',
    ];

    public function search()
    {
        $this->validate();

        $this->isProcessing = true;

        try {
            // Buscar o candidato com os relacionamentos necessários
            $candidate = Candidate::with([
                'course',
                'juryDistributions.jury.room.school', // Relações: Júri, Sala e Escola
                'juryDistributions.disciplina',      // Relação: Disciplina
            ])->where('id', $this->candidateNumber)
              ->first();

            if ($candidate) {
                $this->candidateData = $candidate;

                $this->toast()
                    ->success('Sucesso!', 'Dados do candidato encontrados.')
                    ->send();
            } else {
                $this->toast()
                    ->error('Erro', 'Nenhum candidato foi encontrado com o número fornecido.')
                    ->send();
                $this->candidateData = null;
            }
        } catch (\Exception $e) {
            $this->toast()
                ->error('Erro', 'Ocorreu um erro ao processar sua solicitação.')
                ->send();
        } finally {
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.search-candidate-jury')
            ->extends('layouts.recover')
            ->section('content');
    }
}
