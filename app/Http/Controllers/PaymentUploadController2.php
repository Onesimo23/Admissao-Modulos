<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentUploadController extends Controller
{
    public function showUploadForm()
    {
        // Buscar os últimos pagamentos confirmados
        $confirmedPayments = Payment::where('status', 1)
            ->orderBy('date_payment', 'desc')
            ->take(100)
            ->get();

        return view('upload-form', [
    'confirmedPayments' => $confirmedPayments,
    'message' => "Esta é uma mensagem de teste!"
]);

    }

    public function uploadAndVerifyPayments(Request $request)
    {
        $request->validate([
            'payment_file' => 'required|file|mimes:txt'
        ]);

        if (!$request->hasFile('payment_file')) {
            return redirect()->back()
                ->with('error', 'Nenhum arquivo foi enviado.')
                ->withInput();
        }

        $file = $request->file('payment_file');
        
        if (!$file->isValid()) {
            return redirect()->back()
                ->with('error', 'O arquivo enviado é inválido.')
                ->withInput();
        }

        try {
            $content = $file->get();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível ler o arquivo: ' . $e->getMessage())
                ->withInput();
        }

        $lines = explode("\n", $content);
        $totalLines = count($lines);
        $processedCount = 0;
        $errorCount = 0;
        $processingLog = [];

        foreach ($lines as $lineNumber => $line) {
            $lineNumber++; // Para mostrar números de linha começando em 1
            
            if (strlen(trim($line)) > 30) {
                $result = $this->processPaymentLine($line);
                if ($result['success']) {
                    $processedCount++;
                    $processingLog[] = [
                        'line' => $lineNumber,
                        'status' => 'success',
                        'reference' => $result['reference'],
                        'message' => 'Pagamento processado com sucesso'
                    ];
                } else {
                    $errorCount++;
                    $processingLog[] = [
                        'line' => $lineNumber,
                        'status' => 'error',
                        'reference' => $result['reference'],
                        'message' => $result['message']
                    ];
                }
            } else if (strlen(trim($line)) > 0) {
                $errorCount++;
                $processingLog[] = [
                    'line' => $lineNumber,
                    'status' => 'error',
                    'message' => 'Linha com formato inválido'
                ];
            }
        }

        // Buscar os pagamentos confirmados após o processamento
        $confirmedPayments = Payment::where('status', 1)
            ->orderBy('date_payment', 'desc')
            ->take(100)
            ->get();

        $summary = [
            'total' => $totalLines,
            'processed' => $processedCount,
            'failed' => $errorCount,
        ];

        // Armazenar o log de processamento em um arquivo
        $logContent = "Log de Processamento - " . now()->format('d/m/Y H:i:s') . "\n\n";
        foreach ($processingLog as $log) {
            $logContent .= "Linha {$log['line']}: {$log['status']} - {$log['message']}\n";
        }
        Storage::put('payment_processing_logs/' . now()->format('Y-m-d_His') . '.log', $logContent);

        return view('upload-form', [
            'summary' => $summary,
            'confirmedPayments' => $confirmedPayments,
            'message' => "Processamento concluído. Pagamentos atualizados: $processedCount. Erros: $errorCount"
        ]);
    }

    private function processPaymentLine($line)
    {
        $reference = substr($line, 1, 11);
        $value = intval(substr($line, 12, 14));
        
        // Extrai e formata a data e hora
        try {
            $paymentDateTime = substr($line, 48, 4) . '-' . 
                             substr($line, 46, 2) . '-' . 
                             substr($line, 44, 2) . ' ' . 
                             substr($line, 52, 2) . ':' . 
                             substr($line, 54, 2);

            // Valida a data
            if (!Carbon::createFromFormat('Y-m-d H:i', $paymentDateTime)) {
                return [
                    'success' => false,
                    'reference' => $reference,
                    'message' => 'Data/hora inválida no arquivo'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'reference' => $reference,
                'message' => 'Erro ao processar data/hora'
            ];
        }

        // Busca o pagamento no banco de dados
        $payment = Payment::where('reference', $reference)
                        ->where('value', $value)
                        ->where('status', 0)
                        ->first();

        if ($payment) {
            try {
                $payment->status = 1;
                $payment->date_payment = $paymentDateTime;
                $payment->save();
                
                return [
                    'success' => true,
                    'reference' => $reference
                ];
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'reference' => $reference,
                    'message' => 'Erro ao salvar pagamento: ' . $e->getMessage()
                ];
            }
        }

        return [
            'success' => false,
            'reference' => $reference,
            'message' => 'Pagamento não encontrado ou já processado'
        ];
    }
}