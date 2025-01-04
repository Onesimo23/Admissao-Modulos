<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentUploadController extends Controller
{
    public function showUploadForm(Request $request)
    {
        $query = Payment::query();

        if ($request->filled('date_from')) {
            $dateFrom = Carbon::createFromFormat('d/m/Y', $request->date_from)->startOfDay();
            $query->where('date_payment', '>=', $dateFrom);
        }

        if ($request->filled('date_to')) {
            $dateTo = Carbon::createFromFormat('d/m/Y', $request->date_to)->endOfDay();
            $query->where('date_payment', '<=', $dateTo);
        }

        if ($request->filled('doc')) {
            $query->where('doc', 'like', '%' . $request->doc . '%');
        }

        $confirmedPayments = $query->orderBy('date_payment', 'desc')
                                  ->paginate(30);

        return view('upload-form', ['confirmedPayments' => $confirmedPayments]);
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
            // Obter o nome original do arquivo
            $originalFileName = $file->getClientOriginalName();
            // Pegar os primeiros 10 caracteres
            $fileNamePrefix = substr($originalFileName, 0, 10);
            
            // Verificar se o arquivo já foi processado
            $year = date('Y');
            $month = date('m');
            $filePath = "payment_files/{$year}/{$month}/";
            
            // Procurar por arquivos existentes com o mesmo nome
            $existingFiles = Storage::files($filePath);
            foreach ($existingFiles as $existingFile) {
                if (str_contains($existingFile, $fileNamePrefix)) {
                    return redirect()->back()
                        ->with('error', 'Este arquivo já foi processado anteriormente.')
                        ->withInput();
                }
            }

            // Verificar pelo conteúdo do arquivo (hash MD5)
            $contentHash = md5($content);
            $hashFilePath = "payment_files/processed_hashes.json";
            
            $processedHashes = [];
            if (Storage::exists($hashFilePath)) {
                $processedHashes = json_decode(Storage::get($hashFilePath), true) ?? [];
            }
            
            if (in_array($contentHash, $processedHashes)) {
                return redirect()->back()
                    ->with('error', 'Este arquivo já foi processado anteriormente (conteúdo duplicado).')
                    ->withInput();
            }
            
            // Se chegou aqui, o arquivo é novo
            // Adicionar hash à lista de processados
            $processedHashes[] = $contentHash;
            Storage::put($hashFilePath, json_encode($processedHashes));
            
            // Mover o arquivo para o storage
            $fileName = now()->format('Ymd_His') . '_' . $fileNamePrefix;
            Storage::makeDirectory($filePath);
            Storage::put($filePath . $fileName, $content);
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível processar o arquivo: ' . $e->getMessage())
                ->withInput();
        }

        $lines = explode("\n", $content);
        $totalLines = count($lines);
        $processedCount = 0;
        $errorCount = 0;
        $processingLog = [];

        foreach ($lines as $lineNumber => $line) {
            $lineNumber++; 
            
            if (strlen(trim($line)) > 30) {
                $result = $this->processPaymentLine($line, $fileNamePrefix);
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

        $confirmedPayments = Payment::where('status', 1)
            ->orderBy('date_payment', 'desc')
            ->take(100)
            ->get();

        $summary = [
            'total' => $totalLines,
            'processed' => $processedCount,
            'failed' => $errorCount,
            'stored_file' => $filePath . $fileName
        ];

        $logContent = "Log de Processamento - " . now()->format('d/m/Y H:i:s') . "\n";
        $logContent .= "Arquivo processado: " . $filePath . $fileName . "\n\n";
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

    private function processPaymentLine($line, $fileNamePrefix)
    {
        $reference = substr($line, 1, 11);
        $value = intval(substr($line, 12, 14));
        
        try {
            $paymentDateTime = substr($line, 48, 4) . '-' . 
                             substr($line, 46, 2) . '-' . 
                             substr($line, 44, 2) . ' ' . 
                             substr($line, 52, 2) . ':' . 
                             substr($line, 54, 2);

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

        $payment = Payment::where('reference', $reference)
                        ->where('value', $value)
                        ->where('status', 0)
                        ->first();

        if ($payment) {
            try {
                $payment->status = 1;
                $payment->date_payment = $paymentDateTime;
                $payment->doc = $fileNamePrefix;
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