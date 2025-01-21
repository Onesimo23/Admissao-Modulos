<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
        Consulta do Local e Sala de Realização de Exames de Admissão
    </h2>

    <hr> <center> 
        <span>
            <h4>Apenas para Candidatos do Regime Laboral
            </h4>
        </span>
    </center>
<hr>
<br>
    <form wire:submit.prevent="search" class="space-y-4">
        <div class="mb-4">
            <label for="candidateNumber" class="block text-gray-700 text-sm font-bold mb-2">Número do Candidato</label>
            <input type="number" id="candidateNumber" wire:model.defer="candidateNumber" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            @error('candidateNumber') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" wire:loading.attr="disabled">
                <span wire:loading.remove>Pesquisar</span>
                <span wire:loading>Verificando...</span>
            </button>
        </div>
    </form>

    @if(session()->has('error'))
    <div class="mt-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    @if($candidateData)
    <div class="mt-6">
        <h3 class="text-lg font-semibold">Dados do Candidato:</h3>
        <p><strong>Nome:</strong> {{ $candidateData->name }}</p>
        <p><strong>Curso:</strong> {{ $candidateData->course->name ?? 'N/A' }}</p>
        <p><strong>Província:</strong> {{ $candidateData->province->name ?? 'N/A' }}</p>

        @if($candidateData->juryDistributions->isNotEmpty())
        <h4 class="text-md font-semibold mt-4">Distribuições de Júri:</h4>
        <div class="overflow-x-auto mt-2">
            <div class="bg-white shadow-sm rounded-lg p-6">
                @php
                // Ordenar os exames pela data e hora
                $sortedExams = $candidateData->juryDistributions->sortBy(function($distribution, $key) {
                $horarioDisciplina1 = $distribution->disciplina->horario_disciplina1
                ? \Carbon\Carbon::parse($distribution->disciplina->horario_disciplina1)
                : null;
                $horarioDisciplina2 = $distribution->disciplina->horario_disciplina2
                ? \Carbon\Carbon::parse($distribution->disciplina->horario_disciplina2)
                : null;

                // Retornar a data mais próxima
                return $horarioDisciplina1 ?? $horarioDisciplina2 ?? \Carbon\Carbon::now()->addYears(100);
                });
                @endphp

                @foreach($sortedExams as $index => $distribution)
                <div class="mb-6 border-b border-gray-300 pb-4">
                    <h2 class="text-lg font-semibold text-gray-700">
                        Exame {{ $index + 1 }}
                    </h2>
                    <div class="mt-4">
                        {{-- Disciplina --}}
                        <p class="text-sm text-gray-500"><strong>Disciplina:</strong>
                            {{ $index === 0 ? $distribution->disciplina->disciplina1 ?? 'N/A' : $distribution->disciplina->disciplina2 ?? 'N/A' }}
                        </p>

                        {{-- Informações de Horário --}}
                        <p class="text-sm text-gray-500">
                            <strong>Dia:</strong>
                            {{ $index === 0 
                        ? ($distribution->disciplina->horario_disciplina1 
                            ? \Carbon\Carbon::parse($distribution->disciplina->horario_disciplina1)->format('d/m/Y') 
                            : 'N/A') 
                        : ($distribution->disciplina->horario_disciplina2 
                            ? \Carbon\Carbon::parse($distribution->disciplina->horario_disciplina2)->format('d/m/Y') 
                            : 'N/A') 
                    }}
                        </p>
                        <p class="text-sm text-gray-500">
                            <strong>Hora de Chegada:</strong>
                            {{ $index === 0 
                        ? ($distribution->disciplina->horario_disciplina1 
                            ? \Carbon\Carbon::parse($distribution->disciplina->horario_disciplina1)->subMinutes(30)->format('H:i') 
                            : 'N/A') 
                        : ($distribution->disciplina->horario_disciplina2 
                            ? \Carbon\Carbon::parse($distribution->disciplina->horario_disciplina2)->subMinutes(30)->format('H:i') 
                            : 'N/A') 
                    }}
                        </p>
                        <p class="text-sm text-gray-500">
                            <strong>Hora de Início:</strong>
                            {{ $index === 0 
                        ? ($distribution->disciplina->horario_disciplina1 
                            ? \Carbon\Carbon::parse($distribution->disciplina->horario_disciplina1)->format('H:i') 
                            : 'N/A') 
                        : ($distribution->disciplina->horario_disciplina2 
                            ? \Carbon\Carbon::parse($distribution->disciplina->horario_disciplina2)->format('H:i') 
                            : 'N/A') 
                    }}
                        </p>

                        {{-- Escola e Sala --}}
                        <p class="text-sm text-gray-500"><strong>Escola:</strong>
                            {{ $distribution->jury->room->school->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-500"><strong>Sala do Exame:</strong>
                            {{ $distribution->jury->room->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>


        </div>
        @else
        <div class="mt-4 p-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg">
            Nenhuma distribuição de júri encontrada para este candidato.
        </div>
        @endif
    </div>
    @endif
</div>