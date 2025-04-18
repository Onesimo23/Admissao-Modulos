<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
    <div class="bg-white shadow-xl rounded-lg p-6">
        <h2 class="text-center text-2xl font-bold text-blue-700 mb-4">
            Consulta do Local e Sala de Realização de Exames de Admissão
        </h2>

        <div class="text-center mb-4 text-sm text-gray-500 italic">
            Apenas para Candidatos do Regime Laboral
        </div>

        <form wire:submit.prevent="search" class="space-y-4 mb-6">
            <div>
                <label for="candidateNumber" class="block text-gray-700 text-sm font-medium mb-1">
                    Número do Candidato
                </label>
                <input type="number" id="candidateNumber" wire:model.defer="candidateNumber"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    required>
                @error('candidateNumber')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-center">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 ease-in-out"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Pesquisar</span>
                    <span wire:loading>Verificando...</span>
                </button>
            </div>
        </form>

        @if(session()->has('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded-md text-sm mb-4">
            {{ session('error') }}
        </div>
        @endif

        @if($candidateData)
        <div class="space-y-3 text-sm text-gray-800">
            <h3 class="text-lg font-semibold text-gray-700 border-b pb-1 mb-3">Dados do Candidato</h3>
            <p><strong>Nome:</strong> {{ $candidateData->surname }} {{ $candidateData->name }}</p>
            <p><strong>Curso:</strong> {{ $candidateData->course->name ?? 'N/A' }}</p>
            <p><strong>Local do Exame:</strong> {{ $candidateData->localExam->name ?? 'N/A' }}</p>
        </div>

        @if($candidateData->juryDistributions->isNotEmpty())
        <div class="mt-6">
            <h4 class="text-md font-semibold text-gray-600 mb-4">Informações dos Exames</h4>

            @php
            $sortedDistributions = $candidateData->juryDistributions->sortBy(function($distribution) {
            return $distribution->examSubject->exam_date ?? now()->addYears(100);
            });
            @endphp

            <div class="grid grid-cols-1 gap-6">
                @foreach($sortedDistributions as $index => $distribution)
                @php
                $exam = $distribution->examSubject;
                $school = $distribution->room->school ?? null;
                $room = $distribution->room ?? null;
                @endphp

                <div class="border border-gray-200 rounded-lg p-4 shadow-sm bg-blue-50">
                    <h5 class="text-blue-700 font-bold mb-2">Exame {{ $index + 1 }}</h5>

                    <p><strong>📚 Disciplina:</strong> {{ $exam->name ?? 'N/A' }}</p>
                    <p><strong>🚪 Sala do Exame:</strong> {{ $room->name ?? 'N/A' }}</p>
                    <p><strong>🏫 Escola:</strong> {{ $school->name ?? 'N/A' }}</p>
                    <p><strong>📅 Data:</strong>
                        {{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') : 'N/A' }}
                    </p>
                    <p><strong>🕒 Hora de Chegada:</strong>
                        {{ $exam->arrival_time ? \Carbon\Carbon::parse($exam->arrival_time)->format('H:i') : 'N/A' }}
                    </p>
                    <p><strong>⏰ Hora de Início:</strong>
                        {{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('H:i') : 'N/A' }}
                    </p>
                     </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="mt-4 bg-yellow-300 text-yellow-800 p-4 rounded-lg text-sm">
            Nenhuma distribuição de júri encontrada para este candidato.
        </div>
        @endif
        @endif
    </div>
</div>