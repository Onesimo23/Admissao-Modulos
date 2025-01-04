<?php

namespace App\Livewire;

use App\Models\ClassModel;
use App\Models\School;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class ClassModels extends Component
{
    use Interactions;

    public $classModels;
    public $schools;
    public $name;
    public $capacity;
    public $status = true;
    public $school_id;
    public $editing = false;
    public $classModelId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
        'status' => 'required|boolean',
        'school_id' => 'required|exists:schools,id',
    ];

    public function mount()
    {
        $this->classModels = ClassModel::with('school')->get();
        $this->schools = School::all();
    }

    public function store()
    {
        $this->validate();

        if ($this->editing) {
            $classModel = ClassModel::findOrFail($this->classModelId);
            $classModel->update([
                'name' => $this->name,
                'capacity' => $this->capacity,
                'status' => $this->status,
                'school_id' => $this->school_id,
            ]);
            $this->toast()->success('Sucesso', 'Sala atualizada com sucesso!')->send();
        } else {
            ClassModel::create([
                'name' => $this->name,
                'capacity' => $this->capacity,
                'status' => $this->status,
                'school_id' => $this->school_id,
            ]);
            $this->toast()->success('Sucesso', 'Sala criada com sucesso!')->send();
        }

        $this->resetForm();
        $this->classModels = ClassModel::with('school')->get();
    }

    public function edit($id)
    {
        $this->editing = true;
        $classModel = ClassModel::findOrFail($id);
        $this->classModelId = $id;
        $this->name = $classModel->name;
        $this->capacity = $classModel->capacity;
        $this->status = $classModel->status;
        $this->school_id = $classModel->school_id;
    }

    public function delete($id)
    {
        ClassModel::findOrFail($id)->delete();
        $this->toast()->success('Sucesso', 'Sala excluída com sucesso!')->send();
        $this->classModels = ClassModel::with('school')->get();
    }

    private function resetForm()
    {
        $this->editing = false;
        $this->name = '';
        $this->capacity = '';
        $this->status = true;
        $this->school_id = '';
    }

    public function render()
    {
        return view('livewire.class-models');
    }
}

