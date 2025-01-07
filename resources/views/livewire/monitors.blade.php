<div class="p-6">
    <h2 class="text-lg font-semibold mb-4">Gerir Monitores</h2>

    <!-- Formulário -->
    <form wire:submit.prevent="store" class="mb-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nome -->
            <div>
                <x-ts-input label="Nome" wire:model.defer="name" placeholder="Insira o nome do monitor" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div>
                <x-ts-input label="Email" wire:model.defer="email" placeholder="Insira o email do monitor" />
                <small class="text-gray-500">Apenas emails institucionais (@unisave.ac.mz) são aceitos.</small>
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Telefone -->
            <div>
                <x-ts-input label="Telefone" wire:model.defer="phone" placeholder="Insira o telefone do monitor" />
                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Escola -->
            <div>
                <x-ts-select.styled
                    wire:model.live="school_id"
                    label="Escola"
                    :options="$schools->map(fn($school) => ['label' => $school->name, 'value' => $school->id])->toArray()"
                    select="label:label|value:value"
                    placeholder="Selecione uma escola" />
                @error('school_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-ts-select.styled
                    wire:model.live="room_id"
                    label="Sala *"
                    :options="$rooms->map(fn($room) => ['label' => $room->name, 'value' => $room->id])->toArray()"
                    select="label:label|value:value"
                    placeholder="Selecione uma sala"
                    :disabled="empty($rooms)"
                    searchable />
                @error('room_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>



            <!-- Status -->
            <div>
                <x-ts-toggle wire:model.defer="status" label="Ativo" />
            </div>
        </div>

        <x-ts-button type="submit" class="mt-4">
            @if ($editing)
            Atualizar Monitor
            @else
            Salvar Monitor
            @endif
        </x-ts-button>
    </form>

    <!-- Tabela -->
    <table class="min-w-full bg-white rounded-md shadow-md border border-gray-200">
        <thead class="bg-gray-200 text-center">
            <tr>
                <th class="py-3 px-4">#</th>
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">E-mail</th>
                <th class="py-3 px-4">Telefone</th>
                <th class="py-3 px-4">Escola</th>
                <th class="py-3 px-4">Sala</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach($monitors as $monitor)
            <tr class="border-t hover:bg-gray-100">
                <td class="py-3 px-4">{{ $loop->iteration }}</td>
                <td class="py-3 px-4">{{ $monitor->name }}</td>
                <td class="py-3 px-4">{{ $monitor->email }}</td>
                <td class="py-3 px-4">{{ $monitor->phone }}</td>
                <td class="py-3 px-4">{{ $monitor->school->name }}</td>
                <td class="py-3 px-4">{{ $monitor->room->name }}</td>
                <td class="py-3 px-4 space-x-2 flex justify-center items-center">
                    <button wire:click="edit({{ $monitor->id }})" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button wire:click.prevent="delete({{ $monitor->id }})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash-alt"></i>
                    </button>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>