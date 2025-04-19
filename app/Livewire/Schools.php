<?php

namespace App\Livewire;

use App\Models\School;
use App\Models\Province;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Schools extends Component
{
    use Interactions;

    public $schools;
    public $provinces;
    public $name;
    public $priority_level;
    public $province_id;
    public $editing = false;
    public $schoolIdToDelete = null;
    public $status = true;
    public $actionLabel = 'Salvar'; 

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:schools,name,' . $this->schoolIdToDelete,
            'priority_level' => 'required|integer|min:1',
            'province_id' => 'required|exists:provinces,id',
            'status' => 'required|boolean',
        ];
    }

    public function mount()
    {
        $this->schools = School::with('province')->orderBy('priority_level', 'asc')->get();
        $this->provinces = Province::all();
        $this->resetForm();
    }

    public function store()
{
    $this->validate();

    if ($this->editing) {
        $school = School::find($this->schoolIdToDelete);
        $school->update([
            'name' => $this->name,
            'priority_level' => $this->priority_level,
            'province_id' => $this->province_id,
            'status' => $this->status,
        ]);

        $this->toast()->success('Sucesso', 'Escola editada com sucesso!')->send();
    } else {
        School::create([
            'name' => $this->name,
            'priority_level' => $this->priority_level,
            'province_id' => $this->province_id,
            'status' => $this->status,
        ]);

        $this->toast()->success('Sucesso', 'Escola cadastrada com sucesso!')->send();
    }

    $this->resetForm();
    $this->schools = School::with('province')->orderBy('priority_level', 'asc')->get();
}


    public function edit($id)
    {
        $this->editing = true;
        $school = School::find($id);
        $this->name = $school->name;
        $this->priority_level = $school->priority_level;
        $this->province_id = $school->province_id;
        $this->schoolIdToDelete = $id;
        $this->status = $school->status;
        $this->actionLabel = 'Editar';
    }

    public function delete($id)
    {
        $school = School::find($id);

        if (!$school) {
            $this->toast()->error('Erro', 'Escola não encontrada.')->send();
            return;
        }

        $this->dialog()
            ->question('Tem certeza?', "Deseja realmente excluir a escola \"{$school->name}\"?")
            ->confirm('Sim, excluir', 'confirmDelete', [$id])
            ->cancel('Cancelar')
            ->send();
    }



    public function confirmDelete(array $params)
    {
        $id = $params[0];
        $school = School::find($id);

        if ($school) {
            $school->delete();

            $this->toast()->success('Sucesso', 'Escola excluída com sucesso!')->send();
            $this->schools = School::with('province')->orderBy('priority_level', 'asc')->get();
        } else {
            $this->toast()->error('Erro', 'Escola não encontrada.')->send();
        }
    }


    public function render()
    {
        return view('livewire.schools')->layout('layouts.admin');
    }

    private function resetForm()
    {
        $this->editing = false;
        $this->name = '';
        $this->priority_level = '';
        $this->province_id = '';
        $this->status = true;
        $this->actionLabel = 'Salvar';
    }
}
