<div>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Recuperar Senha
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                @if($step === 1)
                Verifique seus dados
                @else
                Digite sua nova senha
                @endif
            </p>
        </div>

        @if($step === 1)
        <form wire:submit.prevent="verifyUser" class="space-y-6">
            <div class="space-y-4">
                <x-ts-input
                    wire:model="username"
                    label="Nr de Candidato"
                    placeholder="Digite o Nr de Candidato"
                    icon="user"
                    error="{{ $errors->first('username') }}" />

                <x-ts-input
                    wire:model="document_number"
                    label="Número do Documento"
                    placeholder="Digite o nr documento"
                    icon="identification"
                    error="{{ $errors->first('document_number') }}" />
            </div>

            <x-ts-button
                type="submit"
                class="w-full"
                primary>
                Verificar Dados
            </x-ts-button>
        </form>
        @else
        <form wire:submit.prevent="resetPassword" class="space-y-6">
            <div class="space-y-4">
                <x-ts-input
                    wire:model="new_password"
                    label="Nova Senha"
                    type="password"
                    placeholder="Digite sua nova senha"
                    icon="lock-closed"
                    error="{{ $errors->first('new_password') }}" />

                <x-ts-input
                    wire:model="new_password_confirmation"
                    label="Confirmar Nova Senha"
                    type="password"
                    placeholder="Confirme sua nova senha"
                    icon="lock-closed"
                    error="{{ $errors->first('new_password_confirmation') }}" />
            </div>

            <x-ts-button
                type="submit"
                class="w-full"
                primary>
                Redefinir Senha
            </x-ts-button>
        </form>
        @endif

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Voltar para o login
            </a>
        </div>
    </div>
</div>