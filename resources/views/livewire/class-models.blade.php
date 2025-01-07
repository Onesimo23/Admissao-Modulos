<div class="p-6">
    <h2 class="text-lg font-semibold mb-4">Gerir Salas</h2>

    <!-- Formulário para adicionar ou editar -->
    <form wire:submit.prevent="store" class="mb-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-ts-input label="Nome da Sala" wire:model.defer="name" placeholder="Nome da Sala" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <x-ts-input label="Capacidade" wire:model.defer="capacity" type="number" placeholder="Capacidade" />
                @error('capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <x-ts-select.styled
                    label="Escola"
                    :options="$schools->map(fn($s) => ['label' => $s->name, 'value' => $s->id])->toArray()"
                    select="label:label|value:value"
                    wire:model.defer="school_id"
                />
                @error('school_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
             <!-- Nível de Prioridade -->
             <div>
                <x-ts-select.styled
                    label="Nível de Prioridade"
                    :options="[ 
                        ['label' => 'Alto', 'value' => 1],
                        ['label' => 'Baixo', 'value' => 2],
                    ]"
                    select="label:label|value:value"
                    wire:model.defer="priority_level"
                />
                @error('priority_level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <x-ts-checkbox label="Ativa" wire:model.defer="status" />
                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
        <x-ts-button type="submit" class="mt-4">
            {{ $editing ? 'Atualizar Sala' : 'Salvar Sala' }}
        </x-ts-button>
    </form>

    <!-- Tabela de Salas -->
    <table class="min-w-full bg-white rounded-md shadow-md border border-gray-200">
        <thead class="bg-gray-200 text-center">
            <tr>
                <th class="py-3 px-4">#</th>
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Capacidade</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Escola</th>
                <th class="py-2 px-4">Nível de Prioridade</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach($classModels as $room)
                <tr class="border-t hover:bg-gray-100">
                    <td class="py-3 px-4">{{ $loop->iteration }}</td>
                    <td class="py-3 px-4">{{ $room->name }}</td>
                    <td class="py-3 px-4">{{ $room->capacity }}</td>
                    <td class="py-3 px-4">
                        <span class="{{ $room->status ? 'text-green-600' : 'text-red-600' }}">
                            {{ $room->status ? 'Ativa' : 'Inativa' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">{{ $room->school->name }}</td>
                    <td class="py-2 px-4">
                            @if ($room->priority_level === 1)
                                Alto
                            @elseif ($room->priority_level === 2)
                            Baixo
                            @else
                                Baixo
                            @endif
                        </td>
                    <td class="py-3 px-4 space-x-2 flex justify-center items-center">
                        <!-- Botão Editar -->
                        <button wire:click="edit({{ $room->id }})" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Botão Excluir -->
                        <button wire:click="delete({{ $room->id }})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
