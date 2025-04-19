<div class="p-6">
    <h2 class="text-lg font-semibold mb-4">Gerir Salas</h2>

    <!-- Formulário -->
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
                    wire:model.defer="school_id" />
                @error('school_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <x-ts-select.styled
                    label="Nível de Prioridade"
                    :options="[
                        ['label' => 'Alta', 'value' => 1],
                        ['label' => 'Baixa', 'value' => 2]
                    ]"
                    select="label:label|value:value"
                    wire:model.defer="priority_level" />
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
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Capacidade</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Escola</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nível de Prioridade</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($rows as $row)
                <tr class="border-t {{ !$row->status ? 'bg-red-100' : '' }}">
                    <td class="px-4 py-2">{{ $row->id }}</td>
                    <td class="px-4 py-2">{{ $row->name }}</td>
                    <td class="px-4 py-2">{{ $row->capacity }}</td>
                    <td class="px-4 py-2">{{ $row->school->name }}</td>
                    <td class="px-4 py-2">
                        @if ($row->priority_level == 1)
                        <x-ts-badge color="warning">Alta</x-ts-badge>
                        @else
                        <x-ts-badge color="secondary">Baixa</x-ts-badge>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if ($row->status)
                        <x-ts-badge color="success">Ativa</x-ts-badge>
                        @else
                        <x-ts-badge color="danger">Inativa</x-ts-badge>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex space-x-2">
                            <x-ts-button
                                icon="pencil"
                                size="sm"
                                color="info"
                                variant="solid"
                                wire:click="edit({{ $row->id }})"
                                tooltip="Editar" />
                            <x-ts-button
                                icon="trash"
                                size="sm"
                                color="danger"
                                variant="solid"
                                wire:click="delete({{ $row->id }})"
                                tooltip="Excluir" />
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $rows->links() }}
        </div>
    </div>
</div>