<div class="mt-4 sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="text-center text-xl font-bold leading-9 tracking-tight text-blue-500">
        Consultar Estado da Inscrição
    </h2>

    <form wire:submit.prevent="lookupRegistrationStatus" class="space-y-4">
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

    @if ($registration_status)
		<div class="mt-6 p-6 bg-white rounded-lg shadow-md text-gray-800">
			<!-- Estado da Candidatura -->
			<div class="mb-6">
				<h3 class="text-xl text-center font-bold border-b border-gray-200 pb-2">...</h3>
				<ul class="text-sm mt-4 space-y-2 ">
					<li>
						<strong>Nr de Candidato:</strong> 
						<span class="text-gray-600">
							{{ $registration_status['id'] }}
						</span>
					</li>				
					<li>
						<strong>Nome:</strong> 
						<span class="text-gray-600">
							{{ strtoupper($registration_status['name']) }} {{ strtoupper($registration_status['surname']) }}
						</span>
					</li>
					<li><strong>Curso:</strong> <span class="text-gray-600">{{ strtoupper($registration_status['course']) }}</span></li>
					<li><strong>Universidade:</strong> <span class="text-gray-600">{{ strtoupper($registration_status['university']) }}</span></li>
					<li>
						<strong>Regime:</strong> 
						<span class="text-gray-600">{{ strtoupper($registration_status['regime']) }}</span>
					</li>
					<hr>
					<li>
						<strong>Estado da Inscrição:</strong> 
						<span class="{{ $registration_status['status'] === 'CONFIRMADA' ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }}">
							{{ $registration_status['status'] }}
						</span>
					</li>					
				</ul>
			</div>

		</div>

    @endif
</div>