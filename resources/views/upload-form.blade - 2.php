<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Processamento de Pagamentos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-6">Upload de Arquivo de Pagamentos</h2>
				@if(session('error'))
					<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
						{{ session('error') }}
					</div>
				@endif

				@if(session('message'))
					<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
						{{ session('message') }}
					</div>
				@endif


            <form action="{{ route('upload.payments') }}" method="POST" enctype="multipart/form-data" class="mb-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Selecione o arquivo de pagamentos (.txt)
                    </label>
                    <input type="file" 
                           name="payment_file" 
                           accept=".txt"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Processar Pagamentos
                </button>
            </form>
        </div>

        @if(isset($summary))
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold mb-4">Resumo do Processamento</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-gray-100 p-4 rounded">
                    <p class="text-sm text-gray-600">Total de Linhas</p>
                    <p class="text-2xl font-bold">{{ $summary['total'] - 3 }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded">
                    <p class="text-sm text-gray-600">Processados com Sucesso</p>
                    <p class="text-2xl font-bold text-green-600">{{ $summary['processed'] }}</p>
                </div>
                <div class="bg-red-100 p-4 rounded">
                    <p class="text-sm text-gray-600">Não Processados</p>
                    <p class="text-2xl font-bold text-red-600">{{ $summary['failed'] - 2 }}</p>
                </div>
            </div>
        </div>
        @endif

        @if(isset($confirmedPayments) && count($confirmedPayments) > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Pagamentos Confirmados</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Referência
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Valor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data do Pagamento
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($confirmedPayments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $payment->reference }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                MT {{ number_format($payment->value, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($payment->date_payment)->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</body>
</html>