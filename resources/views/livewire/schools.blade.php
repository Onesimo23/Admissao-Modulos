<div class="p-6 bg-white shadow rounded-lg">
    <h2 class="text-xl font-bold mb-6">Gerir Escolas</h2>

    <!-- Formulário para adicionar ou editar escola -->
    <form wire:submit.prevent="store" class="mb-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        ['label' => 'Baixo', 'value' => 2],
                    ]"
                    select="label:label|value:value"
                    wire:model.defer="priority_level" />
                @error('priority_level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Província -->
            <div>
                <x-ts-select.styled
                    label="Província"
                    :options="$provinces->map(fn($province) => ['label' => $province->name, 'value' => $province->id])->toArray()"
                    select="label:label|value:value"
                    wire:model.defer="province_id" />
                @error('province_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Status da Escola -->
            <div class="flex items-center space-x-2">
                <x-ts-toggle
                    label="Escola Ativa?"
                    wire:model.defer="status"
                    :value="true" />
                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Botão de Salvar Escola -->
            <div class="flex ">
                <x-ts-button type="submit" class="mt-4">
                    Salvar Escola
                </x-ts-button>
            </div>
        </div>
    </form>

    <!-- Tabela de Escolas -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-md shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-4 text-left">#</th>
                    <th class="py-3 px-4 text-left">Nome</th>
                    <th class="py-3 px-4 text-left">Nível de Prioridade</th>
                    <th class="py-3 px-4 text-left">Província</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                <tr class="border-t {{ !$school->status ? 'bg-red-100' : '' }}">
                    <td class="py-3 px-4">{{ $loop->iteration }}</td>
                    <td class="py-3 px-4">{{ $school->name }}</td>
                    <td class="py-3 px-4">
                        @if ($school->priority_level === 1)
                        Alto
                        @else
                        Baixo
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ $school->province->name }}</td>
                    <td class="py-3 px-4">
                        @if($school->status)
                        <span class="text-green-600 font-semibold">Ativa</span>
                        @else
                        <span class="text-red-600 font-semibold">Inativa</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 flex justify-center space-x-4">
                        <!-- Botão Editar -->
                        <button wire:click="edit({{ $school->id }})" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Botão Excluir -->
                        <button wire:click="delete({{ $school->id }})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>