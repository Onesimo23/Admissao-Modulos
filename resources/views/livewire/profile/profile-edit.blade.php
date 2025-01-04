<div class="min-h-screen flex flex-col lg:flex-row">
    <!-- Lado Direito: Perfil (se tornará o topo em telas pequenas) -->
    <div class="w-full lg:w-1/4 bg-white p-4 rounded-lg shadow mb-6 lg:mb-0">
        <x-ts-card class="h-full">
            <x-slot:header>
                Perfil  
            </x-slot:header>
            <div class="flex flex-col items-center gap-4 p-6">
                <img src="{{ $profile_photo ? asset('storage/' . $profile_photo) : 'https://via.placeholder.com/150' }}" alt="Foto de Perfil" class="w-32 h-32 rounded-full">
                <div class="text-center">
                    <div class="text-xl font-semibold">{{ $name }}</div>
                    <div class="text-gray-500"> Nome do usuário: {{ $username }}</div>
                    <div class="text-gray-600">{{ $email }}</div>
                    <div class="text-gray-600">{{ $phone }}</div>
                </div>
            </div>
        </x-ts-card>
    </div>

    <!-- Lado Esquerdo: Abas e Conteúdo -->
    <div class="w-full lg:w-3/4 flex flex-col">
        <!-- Abas (visíveis em telas menores e maiores) -->
        <div class="bg-gray-100 p-4 border-b border-gray-200 flex-none">
            <div class="flex">
                <div class="flex-1">
                    <ul class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-4">
                        <li>
                            <a 
                                href="#personal-data" 
                                class="block p-3 rounded shadow hover:bg-gray-300 transition {{ $activeTab === 'personal-data' ? 'bg-gray-300 text-gray-900' : 'bg-gray-100 text-gray-800' }}"
                                wire:click="$set('activeTab', 'personal-data')"
                            >
                                Dados Pessoais
                            </a>
                        </li>
                        <li>
                            <a 
                                href="#change-password" 
                                class="block p-3 rounded shadow hover:bg-gray-300 transition {{ $activeTab === 'change-password' ? 'bg-gray-300 text-gray-900' : 'bg-gray-100 text-gray-800' }}"
                                wire:click="$set('activeTab', 'change-password')"
                            >
                                Alterar Senha
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Conteúdo das Abas -->
        <div class="flex-1 p-4 overflow-y-auto">
            @if ($activeTab === 'personal-data')
                <x-ts-card class="h-full">
                    <x-slot:header>
                        Dados Pessoais
                    </x-slot:header>
                    <div class="p-6">
                        <!-- Formulário para atualização de dados pessoais -->
                        <form wire:submit.prevent="updatePersonalData">
                            <div class="mb-4">
                                <x-ts-input wire:model="name" label="Nome" />
                            </div>
                            <div class="mb-4">
                                <x-ts-input wire:model="username" label="Username" />
                            </div>
                            <div class="mb-4">
                                <x-ts-input wire:model="email" label="Email" />
                            </div>
                            <div class="mb-4">
                                <x-ts-upload wire:model="new_profile_photo" label="Foto de Perfil" />
                            </div>
                            <div class="mb-4">
                                <x-ts-input wire:model="phone" label="Número de Telefone" />
                            </div>
                            <x-ts-button type="submit" color="blue" text="Atualizar Dados Pessoais" />
                        </form>
                    </div>
                </x-ts-card>
            @elseif ($activeTab === 'change-password')
                <x-ts-card class="h-full">
                    <x-slot:header>
                        Alterar Senha
                    </x-slot:header>
                    <div class="p-6">
                        <!-- Formulário para alteração de senha -->
                        <form wire:submit.prevent="updatePassword">
                            <div class="mb-4">
                                <x-ts-input wire:model="current_password" type="password" label="Senha Atual" />
                            </div>
                            <div class="mb-4">
                                <x-ts-input wire:model="new_password" type="password" label="Nova Senha" />
                            </div>
                            <div class="mb-4">
                                <x-ts-input wire:model="new_password_confirmation" type="password" label="Confirmar Nova Senha" />
                            </div>
                            <x-ts-button type="submit" color="blue" text="Alterar Senha" />
                        </form>
                    </div>
                </x-ts-card>
            @endif
        </div>
    </div>
</div>
