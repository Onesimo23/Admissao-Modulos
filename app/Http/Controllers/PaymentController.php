<?php

namespace App\Http\Controllers;

use App\Helpers\PaymentHelper;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function downloadReference(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $candidate = $user->candidate;
        if (!$candidate) {
            return redirect()->route('dashboard')->with('error', 'Candidate information not found.');
        }

        // Cria uma instância de PaymentHelper
        $paymentHelper = new PaymentHelper();

        $entity            = '40207';
        $year              = 25;
        $amount            = 900;

        // Tenta encontrar uma referência existente para este candidato
        $payment = Payment::where('candidate_id', $candidate->id)->first();

        // Se não existir pagamento, cria um novo
        if (!$payment) {
            $baseReference = str_pad($candidate->university_id, 2, '0', STR_PAD_LEFT) . str_pad($candidate->id, 5, '0', STR_PAD_LEFT);
            $completeReference = $paymentHelper->generateCompleteReference($entity, $baseReference, $year, $amount);

            $payment = Payment::create([
                'candidate_id'  => $candidate->id,
                'entity'        => $entity,
                'reference'     => $completeReference,
                'value'         => $amount,
                'status'        => 0,
                'date_payment'  => null,
            ]);
        }
		
		//ex. 01166472425 ->01 = id extensao; 16647 nr candidato; 24 ano; 25 - checkdigit
			
        $candidate->load('course', 'university', 'province');

        $pdf = Pdf::loadView('pdf.reference', [
            'candidate'     => $candidate,
            'entity'        => $payment->entity,
            'reference'     => $payment->reference,
            'amount'        => $payment->value
        ]);

        return $pdf->download('doc.pdf');
    }

    public function downloadConfirmation(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $candidate = $user->candidate;
        if (!$candidate) {
            return redirect()->route('dashboard')->with('error', 'Candidate information not found.');
        }

        // Find the payment with status 1 for this candidate
        $payment = Payment::where('candidate_id', $candidate->id)
            ->where('status', 1)
            ->first();

        // If no payment found with status 1, redirect with error
        if (!$payment) {
            return redirect()->route('dashboard')->with('error', 'No confirmed payment found.');
        }

        $candidate->load('course', 'university', 'province');

        // Generate PDF for payment confirmation
        $pdf = Pdf::loadView('pdf.confirmation', [
            'candidate'     => $candidate,
            'payment'       => $payment,
        ]);

        return $pdf->download('doc.pdf');
    }
}
