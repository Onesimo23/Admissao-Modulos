<div class="mt-4 sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="text-center text-xl font-bold leading-9 tracking-tight text-blue-500">
        Consultar Guia de Pagamento
    </h2>

    <form wire:submit.prevent="lookupPaymentGuide" class="space-y-4">
        <div class="mb-4">
            <label for="candidate_id" class="block text-gray-700 text-sm font-bold mb-2">Informe o Número de Candidato</label>
            <input 
                type="text" 
                id="candidate_id" 
                wire:model="candidate_id" 
                class="ts-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                required>
            @error('candidate_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-center">
            <button type="submit" class="ts-button bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" wire:loading.attr="disabled">
                <span wire:loading.remove>Consultar</span>
                <span wire:loading>Verificando...</span>
            </button>
        </div>
    </form>

    @if ($payment_details)
		<div class="mt-6 p-6 bg-white rounded-lg shadow-md text-gray-800">
			<!-- Seção de Dados da Candidatura -->
			<div class="mb-6">
				<h3 class="text-xl font-bold border-b border-gray-200 pb-2">📋 Dados da Candidatura</h3>
				<ul class="text-sm mt-4 space-y-2">
					<li>
						<strong>Nr de Candidato:</strong> 
						<span class="text-gray-600">
							{{ $payment_details['id'] }}
						</span>
					</li>				
					<li>
						<strong>Nome:</strong> 
						<span class="text-gray-600">
							{{ strtoupper($payment_details['name']) }} {{ strtoupper($payment_details['surname']) }}
						</span>
					</li>
					<li><strong>Curso:</strong> <span class="text-gray-600">{{ strtoupper($payment_details['course']) }}</span></li>
					<li><strong>Universidade:</strong> <span class="text-gray-600">{{ strtoupper($payment_details['university']) }}</span></li>
					<li>
						<strong>Regime:</strong> 
						<span class="text-gray-600">{{ strtoupper($payment_details['regime']) }}</span>
					</li>
				</ul>
			</div>

			<!-- Seção de Dados de Pagamento -->
			<div>
				<h3 class="text-xl font-bold border-b border-gray-200 pb-2">💳 Dados de Pagamento</h3>
				<ul class="text-sm mt-4 space-y-2">
					<li><strong>Entidade:</strong> <span class="text-gray-600">{{ $payment_details['entity'] }}</span></li>
					<li><strong>Referência:</strong> <span class="text-gray-600">{{ $payment_details['reference'] }}</span></li>
					<li><strong>Valor:</strong> <span class="text-gray-600">{{ $payment_details['value'] }}</span></li>
					<li>
						<strong>Estado da Inscrição:</strong> 
						<span class="{{ $payment_details['status'] === 'CONFIRMADA' ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }}">
							{{ $payment_details['status'] }}
						</span>
					</li>
				</ul>
			</div>
			<hr>
			<div class="mt-2 border-t-2 border-gray-300 pt-2 text-sm font-semibold">
				<span class="text-red-500">Atenção:</span> É obrigatório efectuar o pagamento no Millennium BIM
			</div>			
		</div>

    @endif
</div>