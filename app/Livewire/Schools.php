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

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:schools,name,' . $this->schoolIdToDelete,
            'priority_level' => 'required|integer|min:1',
            'province_id' => 'required|exists:provinces,id',
        ];
    }

    public function mount()
    {
        $this->schools = School::with('province')->orderBy('priority_level', 'asc')->get();
        $this->provinces = Province::all();
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
            ]);
        } else {
            School::create([
                'name' => $this->name,
                'priority_level' => $this->priority_level,
                'province_id' => $this->province_id,
            ]);
        }

        $this->toast()->success('Sucesso', 'Escola cadastrada com sucesso!')->send();
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
    }

    public function delete($id)
    {
        $school = School::find($id);

        if ($school) {
            $school->delete();

            $this->toast()->success('Sucesso', 'Escola excluída com sucesso!')->send();
            $this->schools = School::with('province')->orderBy('priority_level', 'asc')->get();
        }
    }

    public function render()
    {
        return view('livewire.schools');
    }

    private function resetForm()
    {
        $this->editing = false;
        $this->name = '';
        $this->priority_level = '';
        $this->province_id = '';
    }
}
