<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Candidate;
use App\Models\Payment;
use TallStackUi\Traits\Interactions;
use function App\Helpers\maskFullName;


class PaymentGuideLookup extends Component
{
    use Interactions;

    public $candidate_id = '';
    public $payment_details = null;
    public $isProcessing = false;

    protected $rules = [
        'candidate_id' => 'required|numeric|digits:5',
    ];
	
	public function messages()
	{
		return [
			'candidate_id.required'    => 'O nr de candidato é obrigatório.',
			'candidate_id.numeric'     => 'O nr de candidato deve ser número.',
			'candidate_id.digits'      => 'O nr de candidato deve ter 5 dígitos.',
		];
	}		

    public function lookupPaymentGuide()
    {
        $this->validate();

        $this->isProcessing = true;

        try {
            // Busca o candidato e o guião de pagamento
            $candidate = Candidate::find($this->candidate_id);

            if ($candidate) {
                //$payment = Payment::where('candidate_id', $candidate->id)->first();
				$payment = Payment::where('candidate_id', $candidate->id)->latest()->first(); // ->latest()  Ordena por `created_at` de forma decrescente

                if ($payment) {
                    $this->payment_details = [
                        'id' 			=> $candidate->id,
                        'name' 			=> maskFullName($candidate->name),
                        'surname' 		=> maskFullName($candidate->surname),
						'course' 		=> $candidate->course->name,
						'university'	=> $candidate->university->name,
						'regime' 		=> $candidate->regime->name,					
                        'entity' 		=> $payment->entity,
                        'reference' 	=> $payment->reference,
                        'value' 		=> number_format($payment->value, 2) . ' MT',
                        'status' 		=> $payment->status ? 'CONFIRMADA' : 'PENDENTE',
                    ];

                    $this->toast()
                        ->success('Sucesso!', 'Guião de pagamento encontrado.')
                        ->send();
                } else {
                    $this->toast()
                        ->error('Erro', 'Nenhum guião de pagamento foi encontrado para este candidato.')
                        ->send();
                }
            } else {
                $this->toast()
                    ->error('Erro', 'Nenhum candidato encontrado com o código fornecido.')
                    ->send();
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
        return view('livewire.payment-guide-lookup')
            ->extends('layouts.recover')
            ->section('content');
    }
}