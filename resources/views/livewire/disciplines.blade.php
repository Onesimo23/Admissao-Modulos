<div class="p-4">
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-lg font-semibold">Cursos e Disciplinas</h2>
        <x-ts-button wire:click="create" class="bg-blue-500 hover:bg-blue-700 text-white">
            <i class="fas fa-plus"></i> Adicionar Curso
        </x-ts-button>
    </div>

    <!-- Informação -->
    <div class="mb-2 p-2 bg-blue-100 text-blue-700 rounded-md">
        Permite gerenciar cursos e as disciplinas de exame, e adicionar novos.
    </div>

    <!-- Filtros e Pesquisa -->
    <div class="mb-2 flex justify-between items-center">
        <x-ts-select.styled
            wire:model="quantity"
            :options="[10, 20, 50]"
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
                    <button wire:click="edit({{ $course->id }})" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button wire:click="delete({{ $course->id }})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash-alt"></i>
                    </button>
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