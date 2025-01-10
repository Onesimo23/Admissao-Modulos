<?php

namespace App\Livewire;

use App\Models\Disciplina;
use App\Models\Course;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Disciplines extends Component
{
    use Interactions;

    public $disciplines, $courses, $disciplineId, $disciplina1, $disciplina2, $peso1, $peso2, $horario_disciplina1, $horario_disciplina2, $course_id;
    public $isEditing = false;

    protected $rules = [
        'disciplina1' => 'required|string|max:255',
        'disciplina2' => 'required|string|max:255',
        'peso1'       => 'required|integer|min:1|max:100',
        'peso2'       => 'required|integer|min:0|max:100',
        'horario_disciplina1' => 'required|date_format:Y-m-d\TH:i',
        'horario_disciplina2' => 'required|date_format:Y-m-d\TH:i',
        'course_id'   => 'required|exists:courses,id',
    ];

    public function mount()
    {
        $this->courses = Course::all();
        $this->loadDisciplines();
    }

    public function updatedPeso1()
    {
        $this->peso2 = 100 - $this->peso1;
    }

    public function loadDisciplines()
    {
        $this->disciplines = Disciplina::with('course')->get();
    }

    public function resetFields()
    {
        $this->disciplina1 = '';
        $this->disciplina2 = '';
        $this->peso1 = '';
        $this->peso2 = '';
        $this->horario_disciplina1 = '';
        $this->horario_disciplina2 = '';
        $this->course_id = '';
        $this->disciplineId = null;
        $this->isEditing = false;
    }

    public function store()
    {
        $this->validate();

        Disciplina::create([
            'disciplina1' => $this->disciplina1,
            'disciplina2' => $this->disciplina2,
            'peso1' => $this->peso1,
            'peso2' => $this->peso2,
            'horario_disciplina1' => $this->horario_disciplina1,
            'horario_disciplina2' => $this->horario_disciplina2,
            'course_id' => $this->course_id,
        ]);

        $this->toast()->success('Sucesso', 'Disciplina criada com sucesso!')->send();
        $this->resetFields();
        $this->loadDisciplines();
    }

    public function edit($id)
    {
        $discipline = Disciplina::findOrFail($id);
        $this->disciplineId = $discipline->id;
        $this->disciplina1 = $discipline->disciplina1;
        $this->disciplina2 = $discipline->disciplina2;
        $this->peso1 = $discipline->peso1;
        $this->peso2 = $discipline->peso2;
        $this->horario_disciplina1 = $discipline->horario_disciplina1->format('Y-m-d\TH:i');
        $this->horario_disciplina2 = $discipline->horario_disciplina2->format('Y-m-d\TH:i');
        $this->course_id = $discipline->course_id;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $discipline = Disciplina::findOrFail($this->disciplineId);
        $discipline->update([
            'disciplina1' => $this->disciplina1,
            'disciplina2' => $this->disciplina2,
            'peso1' => $this->peso1,
            'peso2' => $this->peso2,
            'horario_disciplina1' => $this->horario_disciplina1,
            'horario_disciplina2' => $this->horario_disciplina2,
            'course_id' => $this->course_id,
        ]);

        $this->toast()->success('Sucesso', 'Disciplina atualizada com sucesso!')->send();
        $this->resetFields();
        $this->loadDisciplines();
    }

    public function delete($id)
    {
        Disciplina::findOrFail($id)->delete();
        $this->toast()->success('Sucesso', 'Disciplina excluída com sucesso!')->send();
        $this->loadDisciplines();
    }

    public function render()
    {
        return view('livewire.disciplines');
    }
}
