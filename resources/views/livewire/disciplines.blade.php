<div class="p-6">
    <h2 class="text-lg font-semibold mb-4">Gerir Disciplinas</h2>

    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="mb-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Disciplina 1 -->
            <div>
                <x-ts-input label="Disciplina 1" wire:model.defer="disciplina1" placeholder="Insira o nome da disciplina 1" />
                @error('disciplina1') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Disciplina 2 -->
            <div>
                <x-ts-input label="Disciplina 2" wire:model.defer="disciplina2" placeholder="Insira o nome da disciplina 2" />
                @error('disciplina2') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Peso Disciplina 1 -->
            <div>
                <x-ts-input label="Peso Disciplina 1" type="number" wire:model="peso1" placeholder="Insira o peso da disciplina 1" />
                @error('peso1') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Peso Disciplina 2 -->
            <div>
                <x-ts-input label="Peso Disciplina 2" type="number" wire:model="peso2" placeholder="Será preenchido automaticamente" readonly />
                @error('peso2') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Curso -->
            <div>
                <x-ts-select.styled
                    label="Curso"
                    :options="$courses->map(fn($course) => ['label' => $course->name, 'value' => $course->id])->toArray()"
                    select="label:label|value:value"
                    wire:model.defer="course_id"
                    searchable
                />
                @error('course_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <x-ts-button type="submit" class="mt-4">
            @if ($isEditing)
                Atualizar Disciplina
            @else
                Salvar Disciplina
            @endif
        </x-ts-button>
        @if ($isEditing)
            <x-ts-button type="button" wire:click="resetFields" class="mt-4">Cancelar</x-ts-button>
        @endif
    </form>

    <table class="min-w-full bg-white rounded-md shadow-md border border-gray-200">
        <thead class="bg-gray-200 text-center">
            <tr>
                <th class="py-3 px-4">#</th>
                <th class="py-3 px-4">Disciplina 1</th>
                <th class="py-3 px-4">Disciplina 2</th>
                <th class="py-3 px-4">Peso 1</th>
                <th class="py-3 px-4">Peso 2</th>
                <th class="py-3 px-4">Curso</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach($disciplines as $discipline)
                <tr class="border-t hover:bg-gray-100">
                    <td class="py-3 px-4">{{ $loop->iteration }}</td>
                    <td class="py-3 px-4">{{ $discipline->disciplina1 }}</td>
                    <td class="py-3 px-4">{{ $discipline->disciplina2 }}</td>
                    <td class="py-3 px-4">{{ $discipline->peso1 }}</td>
                    <td class="py-3 px-4">{{ $discipline->peso2 }}</td>
                    <td class="py-3 px-4">{{ $discipline->course->name }}</td>
                    <td class="py-3 px-4 space-x-2 flex justify-center items-center">
                        <button wire:click="edit({{ $discipline->id }})" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="delete({{ $discipline->id }})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
