<div class="p-6">
    <h2 class="text-lg font-semibold mb-4">Cursos e Disciplinas</h2>

    <!-- Filtros e Pesquisa -->
    <div class="mb-4 flex justify-between items-center">
        <x-ts-input wire:model="search" placeholder="Pesquisar cursos..." class="w-1/3" />
        <x-ts-select.styled
            wire:model="quantity"
            :options="[10, 20, 50]"
            label="Mostrar"
            select="label:value|value:value" />
    </div>

    <!-- Tabela de Cursos e Disciplinas -->
    <table class="min-w-full bg-white rounded-md shadow-md border border-gray-200">
        <thead class="bg-gray-200 text-center">
            <tr>
                <th class="py-3 px-4">#</th>
                <th class="py-3 px-4">Curso</th>
                <th class="py-3 px-4">Disciplina 1</th>
                <th class="py-3 px-4">Disciplina 2</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach($rows as $course)
            <tr class="bg-gray-100">
                <td class="py-3 px-4">{{ $loop->iteration }}</td>
                <td class="py-3 px-4">{{ $course->name }}</td>
                <td class="py-3 px-4">
                    {{ $course->disciplina->disciplina1 ?? '-' }}
                </td>
                <td class="py-3 px-4">
                    {{ $course->disciplina->disciplina2 ?? '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="mt-4">
        {{ $rows->links() }}
    </div>
</div>