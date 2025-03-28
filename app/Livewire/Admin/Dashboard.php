<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Payment;
use App\Models\Course; // Importar o modelo Course
use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';
    public $selectedCandidate;
    public $activeTab = 'candidatos'; // Aba ativa padrão

    public function confirmRegistration($candidateId)
    {
        $payment = Payment::where('candidate_id', $candidateId)->first();
        if ($payment) {
            $payment->status = 1;
            $payment->save();
        }
    }

    public function searchCandidate()
    {
        $this->selectedCandidate = User::where('role', 'candidate')
            ->where(function ($query) {
                $query->where('id', $this->search)
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            })->first();
    }

    public function deleteCandidate($candidateId)
    {
        $candidate = User::find($candidateId);
        if ($candidate && $candidate->role === 'candidate') {
            $candidate->delete();
        }
    }

    public function render()
    {
        $users = User::where('role', 'candidate')->get(); // Filtrar apenas candidatos

        $confirmedRegistrations = $users->filter(function ($user) {
            return Payment::where('candidate_id', $user->id)
                ->where('status', 1)
                ->exists();
        })->count();

        $unconfirmedRegistrations = $users->filter(function ($user) {
            return !Payment::where('candidate_id', $user->id)
                ->where('status', 1)
                ->exists();
        })->count();

        $totalCourses = Course::count(); // Contar o número total de cursos

        // Adicionar o nome do curso ao candidato
        $candidates = User::where('role', 'candidate')->get()->map(function ($candidate) {
            $candidate->course_name = Course::find($candidate->course_id)->name ?? 'Sem Curso';
            return $candidate;
        });

        return view('livewire.admin.dashboard', [
            'totalCandidates' => User::where('role', 'candidate')->count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'totalAdmins' => User::where('role', 'admin')->count(),
            'confirmedRegistrations' => $confirmedRegistrations,
            'unconfirmedRegistrations' => $unconfirmedRegistrations,
            'totalCourses' => $totalCourses, // Passar o total de cursos para a view
            'candidates' => $candidates, // Passar candidatos com o nome do curso
            'admins' => User::where('role', 'admin')->get(),
        ])->layout('layouts.admin');
    }
}
