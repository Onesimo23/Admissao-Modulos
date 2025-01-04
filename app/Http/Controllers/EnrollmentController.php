<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate; // Assumindo que a model `Candidate` representa o cadastro de candidatos
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Atualiza os dados de matrícula do candidato.
     */
    public function update(Request $request)
    {

        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'nationality' => 'required|integer',
            'gender' => 'required|integer',
            'marital_status' => 'required|integer',
            'province_id' => 'required|integer',
            'province_district_id' => 'required|integer',
            'document_type' => 'required|integer',
            'document_number' => 'required|string|max:255',
            'nuit' => 'required|string|max:255',
            'cell1' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'pre_university_type' => 'required|integer',
            'pre_university_school_id' => 'required|integer',
            'pre_university_year' => 'required|integer',
            'local_exam' => 'required|integer',
            'university_id' => 'required|integer',
            'regime' => 'required|integer',
            'course_id' => 'required|integer',
        ]);

        // Obtendo o candidato autenticado
        $candidate = Auth::user()->candidate;

        if ($candidate) {
            // Atualizando os dados do candidato
            $candidate->update($validatedData);

            // Redirecionando com mensagem de sucesso
            return redirect()->route('dashboard')->with('success', 'Dados atualizados com sucesso.');
        } else {
            // Se o candidato não existir, redireciona com erro
            return redirect()->route('dashboard')->withErrors('Candidato não encontrado.');
        }
    }
}
