<div class="p-4">
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-lg font-semibold">Cursos e Disciplinas</h2>
        @if (!$editing)
        <x-ts-button wire:click="create" class="bg-blue-500 hover:bg-blue-700 text-white">
            <i class="fas fa-plus"></i> Adicionar Curso
        </x-ts-button>
        @endif
    </div>

    <!-- Informação -->
    <div class="mb-2 p-2 bg-blue-100 text-blue-700 rounded-md">
        Permite gerenciar cursos e as disciplinas de exame, e adicionar novos.
    </div>

    <!-- Formulário -->
    @if ($editing)
    <div class="mb-4 p-4 border border-gray-300 rounded-md bg-white shadow-sm">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nome do Curso</label>
            <x-ts-input wire:model.defer="name" placeholder="Ex: Engenharia Informática" class="w-full" />
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Disciplina 1</label>
                <select wire:model.defer="examSubject1" class="w-full border-gray-300 rounded-md">
                    <option value="">-- Selecione --</option>
                    @foreach($examSubjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                @error('examSubject1') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Disciplina 2</label>
                <select wire:model.defer="examSubject2" class="w-full border-gray-300 rounded-md">
                    <option value="">-- Selecione --</option>
                    @foreach($examSubjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                @error('examSubject2') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-4 flex space-x-2">
            <x-ts-button wire:click="save" class="bg-blue-500 hover:bg-blue-700 text-white">
                <i class="fas fa-save"></i>
                {{ $editing ? 'Atualizar' : 'Salvar' }}
            </x-ts-button>

            <x-ts-button wire:click="resetForm" class="bg-gray-400 hover:bg-gray-500 text-white">
                <i class="fas fa-times"></i> Cancelar
            </x-ts-button>
        </div>
    </div>
    @endif

    <!-- Filtros e Pesquisa -->
    <div class="mb-2 flex justify-between items-center">
        <x-ts-select.styled
            wire:model="quantity"
            :options="[5, 10, 15, 20, 50]"
            label="Mostrar"
            select="label:value|value:value" />
        <div class="flex items-center space-x-2">
            <x-ts-input wire:model="search" placeholder="Pesquisar cursos..." class="w-64" />
            <i class="fas fa-search text-blue-500 cursor-pointer" wire:click="updatingSearch"></i>
        </div>
    </div>

    <!-- Tabela de Cursos e Disciplinas -->
    <table class="min-w-full bg-white rounded-md shadow-md border border-gray-200 text-left">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-3">#</th>
                <th class="py-2 px-3">Curso</th>
                <th class="py-2 px-3">Disciplina 1</th>
                <th class="py-2 px-3">Disciplina 2</th>
                <th class="py-2 px-3">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $course)
            <tr class="bg-gray-100">
                <td class="py-3 px-4">{{ $loop->iteration }}</td>
                <td class="py-3 px-4">{{ $course->name }}</td>
                <td class="py-3 px-4">
                    {{ $course->examSubjects[0]->examSubject->name ?? '-' }}
                </td>
                <td class="py-3 px-4">
                    {{ $course->examSubjects[1]->examSubject->name ?? '-' }}
                </td>

                <td class="py-2 px-3 flex space-x-2">
                    <x-ts-button
                        icon="pencil"
                        size="sm"
                        color="info"
                        variant="solid"
                        wire:click="edit({{ $course->id }})"
                        tooltip="Editar" />

                    <x-ts-button
                        icon="trash"
                        size="sm"
                        color="danger"
                        variant="solid"
                        wire:click="delete({{ $course->id }})"
                        tooltip="Excluir" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="mt-2">
        {{ $rows->links() }}
    </div>
</div>