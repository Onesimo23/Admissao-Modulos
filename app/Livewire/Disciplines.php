<?php

namespace App\Livewire;

use App\Models\Discipline;
use App\Models\Course;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Disciplines extends Component
{
    use Interactions;

    public $disciplines, $courses, $disciplineId, $discipline1, $discipline2, $pesodiscipline1, $pesodiscipline2, $courses_id;
    public $isEditing = false;

    protected $rules = [
        'discipline1' => 'required|string|max:255',
        'discipline2' => 'required|string|max:255',
        'pesodiscipline1' => 'required|integer|min:1|max:100',
        'pesodiscipline2' => 'required|integer|min:0|max:100',
        'courses_id' => 'required|exists:courses,id',
    ];

    public function mount()
    {
        $this->courses = Course::all();
        $this->loadDisciplines();
    }

    public function updatedPesodiscipline1()
    {
        $this->pesodiscipline2 = 100 - $this->pesodiscipline1;
    }

    public function loadDisciplines()
    {
        $this->disciplines = Discipline::with('course')->get();
    }

    public function resetFields()
    {
        $this->discipline1 = '';
        $this->discipline2 = '';
        $this->pesodiscipline1 = '';
        $this->pesodiscipline2 = '';
        $this->courses_id = '';
        $this->disciplineId = null;
        $this->isEditing = false;
    }

    public function store()
    {
        $this->validate();

        Discipline::create([
            'discipline1' => $this->discipline1,
            'discipline2' => $this->discipline2,
            'pesodiscipline1' => $this->pesodiscipline1,
            'pesodiscipline2' => $this->pesodiscipline2,
            'courses_id' => $this->courses_id,
        ]);

        $this->toast()->success('Sucesso', 'Disciplina criada com sucesso!')->send();
        $this->resetFields();
        $this->loadDisciplines();
    }

    public function edit($id)
    {
        $discipline = Discipline::findOrFail($id);
        $this->disciplineId = $discipline->id;
        $this->discipline1 = $discipline->discipline1;
        $this->discipline2 = $discipline->discipline2;
        $this->pesodiscipline1 = $discipline->pesodiscipline1;
        $this->pesodiscipline2 = $discipline->pesodiscipline2;
        $this->courses_id = $discipline->courses_id;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $discipline = Discipline::findOrFail($this->disciplineId);
        $discipline->update([
            'discipline1' => $this->discipline1,
            'discipline2' => $this->discipline2,
            'pesodiscipline1' => $this->pesodiscipline1,
            'pesodiscipline2' => $this->pesodiscipline2,
            'courses_id' => $this->courses_id,
        ]);

        $this->toast()->success('Sucesso', 'Disciplina atualizada com sucesso!')->send();
        $this->resetFields();
        $this->loadDisciplines();
    }

    public function delete($id)
    {
        Discipline::findOrFail($id)->delete();
        $this->toast()->success('Sucesso', 'Disciplina excluída com sucesso!')->send();
        $this->loadDisciplines();
    }

    public function render()
    {
        return view('livewire.disciplines');
    }
}
