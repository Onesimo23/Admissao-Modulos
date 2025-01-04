<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentUploadController extends Controller
{
    public function showUploadForm()
    {
        return view('upload-form');
    }

    public function uploadAndVerifyPayments(Request $request)
    {
        $request->validate([
            'payment_file' => 'required|file|mimes:txt'
        ]);

        if (!$request->hasFile('payment_file')) {
            return redirect()->back()->with('error', 'Nenhum arquivo foi enviado.');
        }

        $file = $request->file('payment_file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'O arquivo enviado é inválido.');
        }

        try {
            $content = $file->get();  // Lê o conteúdo do arquivo
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível ler o arquivo: ' . $e->getMessage());
        }

        $lines = explode("\n", $content);

        $processedCount = 0;
        $errorCount = 0;

        foreach ($lines as $line) {
            if (strlen($line) > 30) {  // Verifica se é uma linha de pagamento válida
                $result = $this->processPaymentLine($line);
                if ($result) {
                    $processedCount++;
                } else {
                    $errorCount++;
                }
            }
        }

        return redirect()->back()->with('message', "Processamento concluído. Pagamentos atualizados: $processedCount. Erros: $errorCount");
    }

	private function processPaymentLine($line)
	{
		// Extrai a referência e o valor da linha
		$reference = substr($line, 1, 11);  // começa a contar a partir do segundo caractere (o índice 1, já que a contagem é zero-indexada) e extrai 11 caracteres a partir dessa posição
		//$value = substr($line, 12, 14);  // Valor: posição 13 até 28 (15 dígitos)
		// Remove zeros à esquerda e converte o valor para decimal
		//$value = ltrim($value, '0');
		//$value = $value / 100;
		$value = intval(substr($line, 12, 14)); // sem casas decimais
		//$value = doubleval(intval(substr($line, 12, 14)) . '.' . intval(substr($line, 26, 2))); // com casas decimais
//dd($value);
			// Extrai a data (posição 44 a 51) e hora (posição 52 a 555)
		$dateString = substr($line, 44, 8);  // Data: 16102024 (16/10/2024)
		$timeString = substr($line, 52, 4);  // Hora: 1303 (13:03)

		// Converte a data e hora para o formato Carbon (Y-m-d H:i)
		$paymentDateTime = substr($line, 48, 4) . '-' . substr($line, 46, 2) . '-' . substr($line, 44, 2)  . ' ' . substr($line, 52, 2) . ':' . substr($line, 54, 2);
		//$paymentDateTime = \Carbon\Carbon::createFromFormat('dmY Hi', $dateString . ' ' . $timeString);
		
		//dd($paymentDateTime);
		
		// Busca o pagamento no banco de dados com a referência, valor e status 0
		$payment = Payment::where('reference', $reference)
						  ->where('value', $value)
						  ->where('status', 0)
						  ->first();

		// Se o pagamento for encontrado, atualiza o status e a data de pagamento
		if ($payment) {
			$payment->status = 1;
			$payment->date_payment = $paymentDateTime;  // Usa a data e hora extraída do arquivo
			$payment->save();
			return true;
		}
	}
}