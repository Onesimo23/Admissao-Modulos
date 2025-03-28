<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Estatísticas -->
        <div class="bg-green-100 shadow-md rounded-lg p-4 border-b-4 border-blue-500">
            <h2 class="text-lg font-semibold mb-2">Total de Candidatos</h2>
            <p class="text-2xl font-bold">{{ $totalCandidates }}</p>
        </div>
        <div class="bg-yellow-100 shadow-md rounded-lg p-4 border-b-4 border-blue-500">
            <h2 class="text-lg font-semibold mb-2">Total de Usuários</h2>
            <p class="text-2xl font-bold">{{ $totalUsers }}</p>
        </div>
        <div class="bg-red-100 shadow-md rounded-lg p-4 border-b-4 border-blue-500">
            <h2 class="text-lg font-semibold mb-2">Total de Administradores</h2>
            <p class="text-2xl font-bold">{{ $totalAdmins }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="bg-blue-100 shadow-md rounded-lg p-4 border-b-4 border-green-500">
            <h2 class="text-lg font-semibold mb-2">Total de Cursos</h2>
            <p class="text-2xl font-bold">{{ $totalCourses }}</p>
        </div>

        <div class="bg-gray-100 shadow-md rounded-lg p-4 border-b-4 border-red-500">
            <h2 class="text-lg font-semibold mb-2">Estudantes com Inscrição Não Confirmada</h2>
            <p class="text-2xl font-bold">{{ $unconfirmedRegistrations }}</p>
        </div>
        <div class="bg-blue-100 shadow-md rounded-lg p-4 border-b-4 border-green-500">
            <h2 class="text-lg font-semibold mb-2">Estudantes com Inscrição Confirmada</h2>
            <p class="text-2xl font-bold">{{ $confirmedRegistrations }}</p>
        </div>
    </div>

    <!-- Abas de gerenciamento -->
    <div class="mt-6">
        <ul class="flex border-b">
            <li class="mr-1">
                <button wire:click="$set('activeTab', 'candidatos')" class="py-2 px-4 font-semibold rounded-t {{ $activeTab === 'candidatos' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                    Gerir Candidatos
                </button>
            </li>
            <li class="mr-1">
                <button wire:click="$set('activeTab', 'administradores')" class="py-2 px-4 font-semibold rounded-t {{ $activeTab === 'administradores' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                    Gerir Administradores
                </button>
            </li>
            <li class="mr-1">
                <button wire:click="$set('activeTab', 'inscricoes')" class="py-2 px-4 font-semibold rounded-t {{ $activeTab === 'inscricoes' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                    Gerir Inscrições
                </button>
            </li>
        </ul>

        <!-- Gerir Candidatos -->
        @if ($activeTab === 'candidatos')
        <div class="p-4 border rounded bg-gray-100">
            <h2 class="text-xl font-bold mb-4">Gerir Candidatos</h2>
            <table id="candidatesTable" class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ID</th>
                        <th class="py-2 px-4 border-b">Nome</th>
                        <th class="py-2 px-4 border-b">Email</th>
                        <th class="py-2 px-4 border-b">Curso</th>
                        <th class="py-2 px-4 border-b">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($candidates as $candidate)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $candidate->id }}</td>
                        <td class="py-2 px-4 border-b">{{ $candidate->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $candidate->email }}</td>
                        <td class="py-2 px-4 border-b">{{ $candidate->course_name }}</td>
                        <td class="py-2 px-4 border-b">
                            <button class="bg-yellow-500 text-white px-2 py-1 rounded">Editar</button>
                            <button class="bg-red-500 text-white px-2 py-1 rounded" wire:click="deleteCandidate({{ $candidate->id }})">Eliminar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Gerir Administradores -->
        @if ($activeTab === 'administradores')
        <div class="p-4 border rounded bg-gray-100">
            <h2 class="text-xl font-bold mb-4">Gerir Administradores</h2>
            <ul>
                @foreach ($admins as $admin)
                <li>{{ $admin->name }} ({{ $admin->email }})</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Gerir Inscrições -->
        @if ($activeTab === 'inscricoes')
        <div class="p-4 border rounded bg-gray-100">
            <h2 class="text-xl font-bold mb-4">Gerir Inscrições</h2>
            <div class="mb-4">
                <input type="text" wire:model="search" placeholder="Buscar por ID ou Nome" class="border p-2 rounded">
                <button wire:click="searchCandidate" class="bg-blue-500 text-white px-4 py-2 rounded">Buscar</button>
            </div>
            @if ($selectedCandidate)
            <div class="p-4 border rounded bg-gray-200">
                <p><strong>Nome:</strong> {{ $selectedCandidate->name }}</p>
                <p><strong>Email:</strong> {{ $selectedCandidate->email }}</p>
                <button wire:click="confirmRegistration({{ $selectedCandidate->id }})" class="bg-green-500 text-white px-4 py-2 rounded">Confirmar Inscrição</button>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        $('#candidatesTable').DataTable();
    });
</script>
@endpush