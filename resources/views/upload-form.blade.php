<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissao - Processamento de Pagamentos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header with improved styling -->
        <header class="bg-white shadow rounded-lg p-8 mb-8">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Sistema de Pagamentos</h1>
                <p class="text-lg text-gray-600">Upload e gestão de arquivos de pagamento</p>
            </div>
        </header>

        <!-- Upload Section with improved styling -->
        <div class="bg-white shadow rounded-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Upload de Arquivo</h2>
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r" role="alert">
                    <p class="font-bold">Erro</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r" role="alert">
                    <p class="font-bold">Sucesso</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('upload.payments') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="max-w-3xl mx-auto">
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Arquivo de Pagamentos (.txt)
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors duration-200">
                            <div class="space-y-2 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="payment_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Selecione um arquivo</span>
                                        <input id="payment_file" name="payment_file" type="file" accept=".txt" class="sr-only" required>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">TXT até 10MB</p>
                            </div>
                        </div>
                        <div id="file-name" class="mt-2 text-sm text-gray-500 text-center"></div>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            Processar Arquivo
                        </button>
                    </div>
                </div>
            </form>
            
            @if(isset($summary))
            <div class="mt-8 max-w-4xl mx-auto">
                <h3 class="text-xl font-bold mb-6 text-center">Resumo do Processamento</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-600 mb-1">Total de Linhas</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $summary['total'] - 3 }}</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-600 mb-1">Processados com Sucesso</p>
                        <p class="text-3xl font-bold text-green-600">{{ $summary['processed'] }}</p>
                    </div>
                    <div class="bg-red-50 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-600 mb-1">Não Processados</p>
                        <p class="text-3xl font-bold text-red-600">{{ $summary['failed'] - 2 }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Filtros e Listagem with improved styling -->
        <div class="bg-white shadow rounded-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-8">Pagamentos Processados</h2>
            
            <!-- Improved Filters -->
            <form action="{{ route('upload.form') }}" method="GET" class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data Inicial</label>
                        <input type="text" name="date_from" value="{{ request('date_from') }}" 
                               class="flatpickr mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data Final</label>
                        <input type="text" name="date_to" value="{{ request('date_to') }}"
                               class="flatpickr mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Documento</label>
                        <input type="text" name="doc" value="{{ request('doc') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                               placeholder="Nº do documento">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('upload.form') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Limpar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Filtrar
                    </button>
                </div>
            </form>

            <!-- Improved Table -->
            <div class="mt-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referência</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidato</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Pagamento</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($confirmedPayments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payment->doc }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->reference }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">MT {{ number_format($payment->value, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->candidate_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->date_payment ? \Carbon\Carbon::parse($payment->date_payment)->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payment->status ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $payment->status ? 'Confirmado' : 'Pendente' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Nenhum pagamento encontrado</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize flatpickr
            flatpickr('.flatpickr', {
                dateFormat: 'd/m/Y',
                locale: 'pt'
            });

            // Show selected filename
            const fileInput = document.getElementById('payment_file');
            const fileNameDiv = document.getElementById('file-name');
            
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    fileNameDiv.textContent = 'Arquivo selecionado: ' + this.files[0].name;
                }
            });

            // Add hover effect to file drop zone
            const dropZone = document.querySelector('.border-dashed');
            fileInput.addEventListener('dragenter', () => dropZone.classList.add('border-blue-500'));
            fileInput.addEventListener('dragleave', () => dropZone.classList.remove('border-blue-500'));
        });
    </script>
</body>
</html>