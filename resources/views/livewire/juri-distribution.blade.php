<div>
    <form wire:submit.prevent="store" class="mb-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Província -->
            <div>
                <x-ts-select.styled
                    label="Província"
                    :options="$provinces->map(fn($p) => ['label' => $p->name, 'value' => $p->id])->toArray()"
                    select="label:label|value:value"
                    wire:model.live="selectedProvince"
                />
                @error('selectedProvince') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Disciplina -->
            <div>
                <x-ts-select.styled
                    label="Disciplina"
                    :options="$disciplines->map(fn($d) => ['label' => $d->disciplina1 . ' / ' . $d->disciplina2, 'value' => $d->id])->toArray()"
                    select="label:label|value:value"
                    wire:model.live="selectedDiscipline"
                />
                @error('selectedDiscipline') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Júris -->
            <div>
                <x-ts-select.styled
                    label="Júri Disponíveis"
                    :options="$juris->map(fn($j) => ['label' => $j->name, 'value' => $j->id])->toArray()"
                    select="label:label|value:value"
                    wire:model.live="selectedJury"
                    :disabled="!$selectedProvince || !$selectedDiscipline"
                />
                @error('selectedJury') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Botão -->
            <div>
                <button type="submit" class="btn btn-primary" @if(!$selectedProvince || !$selectedDiscipline) disabled @endif>
                    Salvar Seleção
                </button>
            </div>
        </div>
    </form>
<!-- Exibir a lista de candidatos para o júri selecionado em uma tabela -->
@if($selectedJury && $candidates->isNotEmpty())
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-4">Candidatos para o Júri Selecionado:</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-sm rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($candidates as $candidate)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $candidate->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $candidate->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $candidate->course->name ?? 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@elseif($selectedJury)
    <div class="mt-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg">
        Nenhum candidato encontrado para este júri.
    </div>
@endif
    <!-- Mensagens de feedback -->
    @if (session()->has('message'))
        <div class="alert alert-success mt-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger mt-4">
            {{ session('error') }}
        </div>
    @endif
</div>