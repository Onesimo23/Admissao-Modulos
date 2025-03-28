<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use App\Models\Payment;
use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';
    public $selectedCandidate;

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

    public function render()
    {
        $users = User::all();

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

        return view('livewire.admin.dashboard', [
            'totalCandidates' => User::where('role', 'candidate')->count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'totalAdmins' => User::where('role', 'admin')->count(),
            'confirmedRegistrations' => $confirmedRegistrations,
            'unconfirmedRegistrations' => $unconfirmedRegistrations,
            'candidates' => User::where('role', 'candidate')->get(),
            'admins' => User::where('role', 'admin')->get(),
        ]);
    }
}
