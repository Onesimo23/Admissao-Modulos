<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function mount(): void
    {
        $this->form->remember = false;
    }
    public function login(): void
    {
        $this->validate();
    
        $loginField = filter_var($this->form->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        $credentials = [
            $loginField => $this->form->login,
            'password' => $this->form->password,
        ];
    
        if (Auth::attempt($credentials, $this->form->remember)) {
            Session::regenerate();
    
            $user = Auth::user();
    
            if ($user->role === 'admin') {
                $this->redirectIntended(route('admin.dashboard'), navigate: true);
            } else {
                $this->redirectIntended(route('dashboard'), navigate: true);
            }
    
        } else {
            $this->addError('form.login', trans('auth.failed'));
        }
    }
    
};
?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        
        <x-input-label for="login" :value="__('Nr de Candidato')" />
        <x-text-input wire:model="form.login" id="login" class="block mt-1 w-full" type="text" name="login" required autofocus autocomplete="Nr de Candidato" />
        <x-input-error :messages="$errors->get('form.login')" class="mt-2" />

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">

            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('reset') }}">
                {{ __('Esqueceu sua senha?') }}
            </a>
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</div>