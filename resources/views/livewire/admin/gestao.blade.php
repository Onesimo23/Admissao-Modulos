<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Estatísticas -->
        <div class="bg-green-100 shadow-md rounded-lg p-4 border-b-4 border-blue-500">
            <h2 class="text-lg font-semibold mb-2">Total de Candidatos</h2>
            <p class="text-2xl font-bold">{{ $totalCandidates }}</p>
        </div>
        <div class="bg-blue-100 shadow-md rounded-lg p-4 border-b-4 border-green-500">
            <h2 class="text-lg font-semibold mb-2">Total de Cursos</h2>
            <p class="text-2xl font-bold">{{ $totalCourses }}</p>
        </div>
        <div class="bg-gray-100 shadow-md rounded-lg p-4 border-b-4 border-red-500">
            <h2 class="text-lg font-semibold mb-2">Candidatos com Inscrição Não Confirmada</h2>
            <p class="text-2xl font-bold">{{ $unconfirmedRegistrations }}</p>
        </div>
        <div class="bg-blue-100 shadow-md rounded-lg p-4 border-b-4 border-green-500">
            <h2 class="text-lg font-semibold mb-2">Candidatos com Inscrição Confirmada</h2>
            <p class="text-2xl font-bold">{{ $confirmedRegistrations }}</p>
        </div>
        <!-- Novas Estatísticas -->
        <div class="bg-yellow-100 shadow-md rounded-lg p-4 border-b-4 border-purple-500">
            <h2 class="text-lg font-semibold mb-2">Candidatos Aprovados</h2>
            <p class="text-2xl font-bold">123</p>
        </div>
        <div class="bg-purple-100 shadow-md rounded-lg p-4 border-b-4 border-yellow-500">
            <h2 class="text-lg font-semibold mb-2">Candidatos Reprovados</h2>
            <p class="text-2xl font-bold">45</p>
        </div>
        <div class="bg-pink-100 shadow-md rounded-lg p-4 border-b-4 border-orange-500">
            <h2 class="text-lg font-semibold mb-2">Inscrições Pendentes</h2>
            <p class="text-2xl font-bold">67</p>
        </div>
    </div>

    <!-- Divisória -->
    <hr class="my-6 border-t-2 border-gray-300">

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
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Gerir Candidatos</h2>
                <x-ts-button wire:click="createCandidate" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Adicionar Candidato
                </x-ts-button>
            </div>

            <!-- Texto ou instrução estilizado -->
            <div class="p-4 mb-4 border-l-4 border-blue-500 bg-blue-100 rounded-md">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 18h.01M21 12c0-4.97-4.03-9-9-9S3 7.03 3 12s4.03 9 9 9 9-4.03 9-9z" />
                    </svg>
                    <p class="text-blue-700 font-medium">
                        Utilize o botão acima para adicionar novos candidatos.<br> Você também pode pesquisar candidatos existentes utilizando o campo de busca abaixo.
                    </p>
                </div>
            </div>

            <!-- Campo de Pesquisa -->
            <div class="flex justify-end mb-4 space-x-2">
                <input
                    type="text"
                    wire:model.defer="search"
                    placeholder="Nome, Número ou Curso"
                    class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button
                    wire:click="searchCandidates"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Pesquisar
                </button>
            </div>

            <!-- Tabela -->
            <table class="min-w-full bg-white rounded-md shadow-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4">#</th>
                        <th class="py-2 px-4">Número</th>
                        <th class="py-2 px-4">Nome</th>
                        <th class="py-2 px-4">Curso</th>
                        <th class="py-2 px-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4">{{ $row->id }}</td>
                        <td class="py-2 px-4">{{ $row->name }}</td>
                        <td class="py-2 px-4">{{ $row->course_name }}</td>
                        @include('components.actions', ['candidate' => $row])
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="mt-4">
                {{ $rows->links() }}
            </div>
        </div>
        @endif

        <!-- Gerir Administradores -->
        @if ($activeTab === 'administradores')
        <div class="p-4 border rounded bg-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Gerir Administradores</h2>
                <x-ts-button wire:click="openAddAdminModal" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Adicionar Administrador
                </x-ts-button>
            </div>

            <!-- Campo de Pesquisa -->
            <div class="flex justify-end mb-4 space-x-2">
                <input
                    type="text"
                    wire:model.defer="searchAdmin"
                    placeholder="Nome ou Email"
                    class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
                <button
                    wire:click="searchAdmins"
                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Pesquisar
                </button>
            </div>

            <!-- Tabela -->
            <table class="min-w-full bg-white rounded-md shadow-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4">#</th>
                        <th class="py-2 px-4">Nome</th>
                        <th class="py-2 px-4">Email</th>
                        <th class="py-2 px-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins['data'] as $admin)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4">{{ $admin['name'] }}</td>
                        <td class="py-2 px-4">{{ $admin['email'] }}</td>
                        <td class="py-2 px-4">
                            <button wire:click="removeAdmin({{ $admin['id'] }})" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Remover
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="mt-4">
                {!! $admins['links'] !!}
            </div>
        </div>
        @endif

        <!-- Modal para Adicionar Administradores -->
        <div x-data="{ open: @entangle('showAddAdminModal') }" x-show="open" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-3/4 max-w-2xl">
                <div class="p-4 border-b">
                    <h2 class="text-xl font-bold">Adicionar Administradores</h2>
                </div>
                <div class="p-4">
                    <!-- Campo de Pesquisa -->
                    <div class="flex justify-end mb-4 space-x-2">
                        <input
                            type="text"
                            wire:model.defer="searchAdmin"
                            placeholder="Nome ou Email"
                            class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
                        <button
                            wire:click="searchNonAdmins"
                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Pesquisar
                        </button>
                    </div>

                    <!-- Lista de Usuários -->
                    <table class="min-w-full bg-white rounded-md shadow-md">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4">#</th>
                                <th class="py-2 px-4">Nome</th>
                                <th class="py-2 px-4">Email</th>
                                <th class="py-2 px-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($nonAdmins))
                            @foreach($nonAdmins as $user)
                            <tr class="border-t">
                                <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4">{{ $user['name'] }}</td>
                                <td class="py-2 px-4">{{ $user['email'] }}</td>
                                <td class="py-2 px-4">
                                    <button wire:click="addAdmin({{ $user['id'] }})" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Adicionar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" class="py-2 px-4 text-center text-gray-500">Nenhum usuário encontrado.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t flex justify-end">
                    <button @click="open = false" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Fechar
                    </button>
                </div>
            </div>
        </div>

        <!-- Gerir Inscrições -->
        @if ($activeTab === 'inscricoes')
        <div class="p-4 border rounded bg-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Gerir Inscrições</h2>
            </div>

            <!-- Texto ou instrução estilizado -->
            <div class="p-4 mb-4 border-l-4 border-blue-500 bg-blue-100 rounded-md">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 18h.01M21 12c0-4.97-4.03-9-9-9S3 7.03 3 12s4.03 9 9 9 9-4.03 9-9z" />
                    </svg>
                    <p class="text-blue-700 font-medium">
                        Utilize o campo de busca abaixo para localizar candidatos.<br> Após localizar, você pode confirmar a inscrição diretamente.
                    </p>
                </div>
            </div>

            <!-- Campo de Pesquisa -->
            <div class="flex justify-end mb-4 space-x-2">
                <input
                    type="text"
                    wire:model.defer="search"
                    placeholder="Buscar por ID ou Nome"
                    class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button
                    wire:click="searchCandidate"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Buscar
                </button>
            </div>

            <!-- Resultado da Busca -->
            @if ($selectedCandidate)
            <div class="p-4 border rounded bg-gray-200">
                <h3 class="text-lg font-semibold mb-2">Informações do Candidato</h3>
                <p><strong>Número:</strong> {{ $selectedCandidate->id }}</p>
                <p><strong>Nome:</strong> {{ $selectedCandidate->name }}</p>
                <p><strong>Email:</strong> {{ $selectedCandidate->email }}</p>
                <p><strong>Status:</strong>
                    @if ($selectedCandidate->payment && $selectedCandidate->payment->status === 1)
                    <span class="text-green-600 font-bold">Confirmado</span>
                    @else
                    <span class="text-red-600 font-bold">Pendente</span>
                    @endif
                </p>
                <div class="mt-4">
                    @if (!$selectedCandidate->payment || $selectedCandidate->payment->status !== 1)
                    <x-ts-button wire:click="confirmRegistration({{ $selectedCandidate->id }})" color="green" class="mt-2">
                        Confirmar Inscrição
                    </x-ts-button>
                    @else
                    <p class="text-green-600 font-bold">Inscrição já confirmada.</p>
                    @endif
                </div>
            </div>
            @else
            <p class="text-gray-500">Nenhum candidato encontrado.</p>
            @endif
        </div>
        @endif
    </div>
</div>