<?php

namespace App\Livewire\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Reset extends Component
{
    use Interactions;

    public $username = '';
    public $document_number = '';
    public $new_password = '';
    public $new_password_confirmation = '';
    public $step = 1;

    protected $rules = [
        'username' => 'required|min:5',
        'document_number' => 'required',
        'new_password' => 'required|min:4|confirmed',
        'new_password_confirmation' => 'required'
    ];

    protected $messages = [
        'username.required' 			=> 'O username é obrigatório',
        'username.min' 					=> 'O username deve ter pelo menos 5 caracteres',
        'document_number.required' 		=> 'O número do documento é obrigatório',
        'new_password.required' 		=> 'A nova senha é obrigatória',
        'new_password.min' 				=> 'A nova senha deve ter pelo menos 4 caracteres',
        'new_password.confirmed' 		=> 'As senhas não coincidem',
    ];

public function verifyUser()
{
    
    $validated = $this->validate([
        'username' => 'required|min:5',
        'document_number' => 'required'
    ]);
    
    $user = User::where('username', $this->username)
                ->where('document_number', $this->document_number)
                ->first();
    
    if (!$user) {
        $this->addError('username', 'Os dados informados não correspondem a nenhum usuário.');
        $this->addError('document_number', 'Os dados informados não correspondem a nenhum usuário.');
        
        $this->toast()
            ->error('Erro na Verificação', 'Os dados informados não correspondem a nenhum usuário.')
            ->send();
        return;
    }
    $this->step = 2;
    
    $this->toast()
        ->success('Verificação bem sucedida', 'Por favor, defina sua nova senha.')
        ->send();
}
    
public function resetPassword()
{
    $validated = $this->validate([
        'new_password' => 'required|min:4|confirmed',
        'new_password_confirmation' => 'required'
    ]);
    
    // Verificação do usuário
    $user = User::where('username', $this->username)
                ->where('document_number', $this->document_number)
                ->first();
    
    if (!$user) {
        $this->addError('new_password', 'Usuário não encontrado.');
        $this->toast()
            ->error('Erro', 'Usuário não encontrado.')
            ->send();
        return;
    }
    
    // Atualização da senha
    $user->password = Hash::make($this->new_password);
    $user->save();
    
    $this->toast()
        ->success('Sucesso!', 'Sua senha foi redefinida com sucesso.')
        ->send();
    
    return redirect()->route('login');
}

    public function render()
    {
        return view('livewire.livewire.reset')
            ->extends('layouts.recover')
            ->section('content');
    }
}