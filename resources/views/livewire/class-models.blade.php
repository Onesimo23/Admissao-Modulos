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
    <div>
        <x-ts-table :headers="$headers" :rows="$rows->items()" filter   :quantity="[2, 5, 10]" />
        {{ $rows->links() }}
    </div>
</div>
