<?php

namespace App\Livewire\Admin;

use App\Models\Candidate;
use App\Models\Course;
use App\Models\User;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class Gestao extends Component
{
    use WithPagination;

    public ?int $quantity = 5;
    public ?string $search = null;
    public ?string $searchAdmin = null;
    public $activeTab = 'candidatos';
    public $selectedCandidate;
    public $admins;
    public $nonAdmins;
    public $showAddAdminModal = false;

    public function mount()
    {
        $this->loadAdmins();
        $this->nonAdmins = [];
    }

    public function getHeaders(): array
    {
        return [
            ['index' => 'id', 'label' => 'Número'],
            ['index' => 'name', 'label' => 'Nome'],
            ['index' => 'course_name', 'label' => 'Curso'],
            ['index' => 'actions', 'label' => 'Ações'],
        ];
    }

    public function getRows()
    {
        return Candidate::query()
            ->with('course')
            ->when($this->search, function (Builder $query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('id', 'like', "%{$this->search}%")
                    ->orWhereHas('course', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->paginate($this->quantity)
            ->through(function ($candidate) {
                $candidate->course_name = $candidate->course->name ?? 'Sem Curso';
                return $candidate;
            })
            ->withQueryString();
    }

    public function searchCandidates()
    {
        $this->resetPage();
    }

    public function searchCandidate()
    {
        $this->selectedCandidate = Candidate::with('payment')
            ->where('name', 'like', "%{$this->search}%")
            ->orWhere('id', 'like', "%{$this->search}%")
            ->first();

        if (!$this->selectedCandidate) {
            $this->toast()->error('Erro', 'Candidato não encontrado ou ainda não baixou o guia de pagamento.')->send();
        }
    }

    public function confirmRegistration($candidateId)
    {
        $candidate = Candidate::find($candidateId);

        if (!$candidate) {
            $this->toast()->error('Erro', 'Candidato não encontrado.')->send();
            return;
        }

        if (!$candidate->payment) {
            $this->toast()->error('Erro', 'Candidato ainda não baixou o guia de pagamento.')->send();
            return;
        }

        $payment = $candidate->payment;
        if (!$payment) {
            $payment = $candidate->payments()->create([
                'status' => 1,
                'value' => 0,
                'entity' => 'default_entity',
            ]);
        } else {
            $payment->update(['status' => 1]);
        }

        $this->toast()->success('Sucesso', 'Inscrição confirmada com sucesso!')->send();
        $this->selectedCandidate = $candidate->fresh(['payment']);
    }

    public function editCandidate($id)
    {
        $candidate = Candidate::find($id);
        if ($candidate) {
            $this->selectedCandidate = $candidate;
            $this->toast()->info('Informação', 'Modo de edição ativado.')->send();
        }
    }

    public function deleteCandidate($id)
    {
        $candidate = Candidate::find($id);
        if ($candidate) {
            $candidate->delete();
            $this->toast()->success('Sucesso', 'Candidato excluído com sucesso!')->send();
        }
    }

    public function loadAdmins()
    {
        $adminsPagination = User::where('role', 'admin')->paginate($this->quantity);
        $this->admins = [
            'data' => $adminsPagination->items(),
            'links' => $adminsPagination->links()->render(),
        ];
    }

    public function searchAdmins()
    {
        $this->resetPage();
        $this->loadAdmins();
    }

    public function openAddAdminModal()
    {
        $this->nonAdmins = [];
        $this->searchNonAdmins();
        $this->showAddAdminModal = true;
    }

    public function searchNonAdmins()
    {
        $this->nonAdmins = User::where('role', '!=', 'admin')
            ->when($this->searchAdmin, function ($query) {
                $query->where('name', 'like', "%{$this->searchAdmin}%")
                    ->orWhere('email', 'like', "%{$this->searchAdmin}%");
            })
            ->get()
            ->toArray();
    }

    public function addAdmin($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->role = 'admin';
            $user->save();
            $this->loadAdmins();
            $this->toast()->success('Sucesso', 'Administrador adicionado com sucesso!')->send();
        }
    }

    public function removeAdmin($userId)
    {
        $user = User::find($userId);
        if ($user && $user->role === 'admin') {
            $user->role = null;
            $user->save();
            $this->loadAdmins();
            $this->toast()->success('Sucesso', 'Administrador removido com sucesso!')->send();
        }
    }

    public function render()
    {
        $totalCandidates = Candidate::count();
        $totalCourses = Course::count();
        $confirmedRegistrations = Payment::where('status', 1)->count();
        $unconfirmedRegistrations = Candidate::whereDoesntHave('payments', function ($query) {
            $query->where('status', 1);
        })->count();

        return view('livewire.admin.gestao', [
            'totalCandidates' => $totalCandidates,
            'totalCourses' => $totalCourses,
            'confirmedRegistrations' => $confirmedRegistrations,
            'unconfirmedRegistrations' => $unconfirmedRegistrations,
            'headers' => $this->getHeaders(),
            'rows' => $this->getRows(),
        ])->layout('layouts.admin');
    }
}
