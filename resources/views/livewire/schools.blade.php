<div class="p-6">
    <h2 class="text-lg font-semibold mb-4">Gerir Escolas</h2>

    <!-- Formulário para adicionar ou editar escola -->
    <form wire:submit.prevent="store" class="mb-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nome da Escola -->
            <div>
                <x-ts-input label="Nome da Escola" wire:model.defer="name" placeholder="Insira o nome da escola" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Nível de Prioridade -->
            <div>
                <x-ts-select.styled
                    label="Nível de Prioridade"
                    :options="[ 
                        ['label' => 'Alto', 'value' => 1],
                        ['label' => 'Normal', 'value' => 2],
                        ['label' => 'Baixo', 'value' => 3],
                    ]"
                    select="label:label|value:value"
                    wire:model.defer="priority_level"
                />
                @error('priority_level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Província -->
            <div>
                <x-ts-select.styled
                    label="Província"
                    :options="$provinces->map(fn($province) => ['label' => $province->name, 'value' => $province->id])->toArray()"
                    select="label:label|value:value"
                    wire:model.defer="province_id"
                />
                @error('province_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Botão de Salvar Escola -->
        <x-ts-button type="submit" class="mt-4">
            Salvar Escola
        </x-ts-button>
    </form>

    <!-- Tabela de Escolas -->
    <div>
        <table class="min-w-full bg-white rounded-md shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4">#</th>
                    <th class="py-2 px-4">Nome</th>
                    <th class="py-2 px-4">Nível de Prioridade</th>
                    <th class="py-2 px-4">Província</th>
                    <th class="py-2 px-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4">{{ $school->name }}</td>
                        <td class="py-2 px-4">
                            @if ($school->priority_level === 1)
                                Alto
                            @elseif ($school->priority_level === 2)
                                Normal
                            @else
                                Baixo
                            @endif
                        </td>
                        <td class="py-2 px-4">{{ $school->province->name }}</td>
                        <td class="py-2 px-4 flex space-x-2">
                            <!-- Botão Editar -->
                            <button wire:click="edit({{ $school->id }})" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-edit"></i> Editar
                            </button>

                            <!-- Botão Excluir -->
                            <button wire:click="delete({{ $school->id }})" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
