<?php

namespace App\Http\Controllers;

use App\Helpers\PaymentHelper;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createReference(Request $request)
    {
        // Crie uma instância de PaymentHelper
        $paymentHelper = new PaymentHelper();

        $entity = '88010'; // Example entity code
        $baseReference = '0216647'; // Example base reference pode separar id de extensao  + nr candidato 
		$year = '25'; // Example month
        $amount = 900; // Payment amount
		//$month = '00'; // Example month

        // Gere a referência completa usando o método da classe PaymentHelper
        $completeReference = $paymentHelper->generateCompleteReference($entity, $baseReference, $year, $amount);

		//02166472425                 02 = id extensao 16647 nr candidato 24 ano 25 - checkdigit
        return response()->json([
            'complete_reference' => $completeReference  
        ]);
    }
}
