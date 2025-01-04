<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    // Exibe o formulário de reset de senha
    public function showForm()
    {
        return view('password-reset'); // View de reset de senha
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Verifica se existe um usuário com o email fornecido
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Gera a nova senha
            $newPassword = 'admissao2025';

            // Atualiza a senha do usuário
            $user->password = Hash::make($newPassword);
            $user->save();

            return redirect()->back()->with('message', 'Sua senha foi resetada para: ' . $newPassword);
        } else {
            return redirect()->back()->withErrors(['email' => 'Não encontramos um usuário com este email.']);
        }
    }
}
