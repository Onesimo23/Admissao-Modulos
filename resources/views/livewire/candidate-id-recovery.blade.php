<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
        Recuperação do Número de Candidato
    </h2>

    <form wire:submit.prevent="recoverCandidateId" class="space-y-4">
        <div class="mb-4">
            <label for="document_or_nuit" class="block text-gray-700 text-sm font-bold mb-2">Nr do Documento ou NUIT</label>
            <input type="text" id="document_or_nuit" wire:model="document_or_nuit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            @error('document_or_nuit') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" wire:loading.attr="disabled">
                <span wire:loading.remove>Recuperar </span>
                <span wire:loading>Verificando...</span>
            </button>
        </div>
    </form>

    @if ($candidate_id)
    <div class="mt-4 text-center text-black font-bold text-lg">
        Número de Candidato: {{ $candidate_id }}
    </div>
    @endif
</div>