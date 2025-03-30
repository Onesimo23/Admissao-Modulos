<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class Disciplines extends Component
{
    use WithPagination;

    public $search = '';
    public $quantity = 5;
    public $editing = false;
    public $courseId;
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingQuantity()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->editing = false;
    }

    public function edit($id)
    {
        $this->editing = true;
        $course = Course::findOrFail($id);
        $this->courseId = $course->id;
        $this->name = $course->name;
    }

    public function delete($id)
    {
        Course::findOrFail($id)->delete();
        $this->toast()->success('Sucesso', 'Curso excluído com sucesso!')->send();
    }

    public function save()
    {
        $this->validate();

        if ($this->editing) {
            $course = Course::findOrFail($this->courseId);
            $course->update(['name' => $this->name]);
            $this->toast()->success('Sucesso', 'Curso atualizado com sucesso!')->send();
        } else {
            Course::create(['name' => $this->name]);
            $this->toast()->success('Sucesso', 'Curso criado com sucesso!')->send();
        }

        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editing = false;
        $this->courseId = null;
        $this->name = '';
    }

    public function render()
{
    $courses = Course::with(['examSubjects.examSubject'])
        ->when($this->search, function ($query) {
            $query->where('name', 'like', "%{$this->search}%");
        })
        ->paginate($this->quantity);

    return view('livewire.disciplines', [
        'rows' => $courses,
    ])->layout('layouts.admin');
}

}
