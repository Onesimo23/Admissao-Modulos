<?php

namespace App\Livewire;

use App\Models\School;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Schools extends Component
{
    use Interactions;

    public $schools;
    public $name;
    public $priority_level;
    public $editing = false;
    public $schoolIdToDelete = null; 
    public $confirmingDelete = false;

    protected $rules = [
        'name' => 'required|string|unique:schools,name|max:255',
        'priority_level' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->schools = School::orderBy('priority_level', 'asc')->get();
    }

    public function store()
    {
        $this->validate();

        // Criar ou editar a escola no banco de dados
        if ($this->editing) {
            $school = School::find($this->schoolIdToDelete); // Encontrar a escola pelo ID
            $school->update([
                'name' => $this->name,
                'priority_level' => $this->priority_level,
            ]);
        } else {
            School::create([
                'name' => $this->name,
                'priority_level' => $this->priority_level,
            ]);
        }

        $this->toast()->success('Successo', 'Escola cadastrada com sucesso!')->send();
        $this->resetForm();
        $this->schools = School::orderBy('priority_level', 'asc')->get(); // Atualiza a lista de escolas
    }

    // Função para editar a escola
    public function edit($id)
    {
        $this->editing = true;
        $school = School::find($id);
        $this->name = $school->name;
        $this->priority_level = $school->priority_level;
        $this->schoolIdToDelete = $id;
    }

    // Função para confirmar exclusão
    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->schoolIdToDelete = $id;
    }

    // Função para cancelar a exclusão
    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->schoolIdToDelete = null;
    }

    // Função para excluir a escola
    public function delete()
    {
        $school = School::find($this->schoolIdToDelete);
        $school->delete();

        $this->toast()->success('Successo', 'Escola excluída com sucesso!')->send();
        $this->confirmingDelete = false;
        $this->schoolIdToDelete = null;
        $this->schools = School::orderBy('priority_level', 'asc')->get(); // Atualiza a lista de escolas
    }

    public function render()
    {
        return view('livewire.schools');
    }

    // Resetar o formulário após o processo
    private function resetForm()
    {
        $this->editing = false;
        $this->name = '';
        $this->priority_level = '';
    }
}
