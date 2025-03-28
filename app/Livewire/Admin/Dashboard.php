<?php

namespace App\Livewire\Admin;

use App\Models\Candidate;
use App\Models\Course;
use App\Models\User;
use App\Models\Payment; // Importação da tabela Payment
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class Dashboard extends Component
{
    use WithPagination; // Removido o uso do trait inexistente

    public ?int $quantity = 5; // Quantidade de registros por página
    public ?string $search = null; // Termo de busca
    public ?string $searchAdmin = null; // Termo de busca para administradores
    public $activeTab = 'candidatos';
    public $selectedCandidate;
    public $admins; // Lista de administradores
    public $nonAdmins; // Lista de usuários não administradores
    public $showAddAdminModal = false; // Controla a exibição do modal

    public function mount()
    {
        $this->loadAdmins();
        $this->nonAdmins = []; // Inicializa como array vazio
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
            ->with('course') // Carregar o relacionamento com o curso
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
        // Apenas atualiza a pesquisa, pois o método getRows já aplica o filtro
        $this->resetPage(); // Reseta para a primeira página ao realizar uma nova pesquisa
    }

    public function searchCandidate()
    {
        $this->selectedCandidate = Candidate::with('payment')
            ->where('name', 'like', "%{$this->search}%")
            ->orWhere('id', 'like', "%{$this->search}%")
            ->first();

        if (!$this->selectedCandidate) {
            $this->emit('toast', ['type' => 'error', 'message' => 'Candidato não encontrado ou ainda não baixou o guia de pagamento.']);
        }
    }

    public function confirmRegistration($candidateId)
    {
        $candidate = Candidate::find($candidateId);

        if (!$candidate) {
            $this->emit('toast', ['type' => 'error', 'message' => 'Candidato não encontrado.']);
            return;
        }

        // Verifica se o candidato possui um guia de pagamento
        if (!$candidate->payment) {
            $this->emit('toast', ['type' => 'error', 'message' => 'Candidato ainda não baixou o guia de pagamento.']);
            return;
        }

        // Atualiza ou cria o pagamento como confirmado
        $payment = $candidate->payment;
        if (!$payment) {
            $payment = $candidate->payments()->create([
                'status' => 1, // Confirmado
                'value' => 0, // Valor padrão
                'entity' => 'default_entity', // Valor padrão para evitar erro
            ]);
        } else {
            $payment->update(['status' => 1]);
        }

        $this->emit('toast', ['type' => 'success', 'message' => 'Inscrição confirmada com sucesso!']);
        $this->selectedCandidate = $candidate->fresh(['payment']);
    }

    public function editCandidate($id)
    {
        $candidate = Candidate::find($id);
        if ($candidate) {
            $this->selectedCandidate = $candidate;
            $this->emit('open-edit-modal'); // Exemplo de evento para abrir modal de edição
        }
    }

    public function deleteCandidate($id)
    {
        $candidate = Candidate::find($id);
        if ($candidate) {
            $candidate->delete();
            $this->emit('toast', ['type' => 'success', 'message' => 'Candidato excluído com sucesso!']);
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
        $this->nonAdmins = []; // Garante que seja um array vazio antes de carregar os dados
        $this->searchNonAdmins(); // Carrega a lista inicial de usuários
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
            $this->emit('toast', ['type' => 'success', 'message' => 'Administrador adicionado com sucesso!']);
        }
    }

    public function removeAdmin($userId)
    {
        $user = User::find($userId);
        if ($user && $user->role === 'admin') {
            $user->role = null;
            $user->save();
            $this->loadAdmins();
            $this->emit('toast', ['type' => 'success', 'message' => 'Administrador removido com sucesso!']);
        }
    }

    public function render()
    {
        $totalCandidates = Candidate::count();
        $totalCourses = Course::count();

        // Verifica inscrições confirmadas e pendentes na tabela payments
        $confirmedRegistrations = Payment::where('status', 1)->count();
        $unconfirmedRegistrations = Candidate::whereDoesntHave('payments', function ($query) {
            $query->where('status', 1);
        })->count();

        return view('livewire.admin.dashboard', [
            'totalCandidates' => $totalCandidates,
            'totalCourses' => $totalCourses,
            'confirmedRegistrations' => $confirmedRegistrations,
            'unconfirmedRegistrations' => $unconfirmedRegistrations,
            'headers' => $this->getHeaders(),
            'rows' => $this->getRows(),
        ])->layout('layouts.admin');
    }
}
