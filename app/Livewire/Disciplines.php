<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\ExamSubject;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use TallStackUi\Traits\Interactions;

class Disciplines extends Component
{
    use WithPagination, Interactions;

    public ?string $search = '';
    public ?int $quantity = 5;

    public bool $editing = false;
    public ?int $courseId = null;
    public string $name = '';

    public $examSubjects = [];
    public $examSubject1 = null;
    public $examSubject2 = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'examSubject1' => 'nullable|different:examSubject2',
        'examSubject2' => 'nullable|different:examSubject1',
    ];

    public function mount()
    {
        $this->examSubjects = ExamSubject::orderBy('name')->get();
    }

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
        $this->editing = true;
    }

    public function edit($id)
    {
        $this->editing = true;

        $course = Course::with('examSubjects')->findOrFail($id);

        $this->courseId = $course->id;
        $this->name = $course->name;
        $this->examSubject1 = $course->examSubjects[0]->exam_subject_id ?? null;
        $this->examSubject2 = $course->examSubjects[1]->exam_subject_id ?? null;
    }

    public function delete($id)
    {
        $course = Course::find($id);

        if (!$course) {
            $this->toast()->error('Erro', 'Curso não encontrado.')->send();
            return;
        }

        $this->dialog()
            ->question('Tem certeza?', "Deseja realmente excluir o curso \"{$course->name}\"?")
            ->confirm('Sim, excluir', 'confirmDelete', $id)
            ->cancel('Cancelar')
            ->send();
    }

    public function confirmDelete($id)
    {
        $course = Course::find($id);

        if (!$course) {
            $this->toast()->error('Erro', 'Curso não encontrado.')->send();
            return;
        }

        if ($course->candidates()->exists()) {
            $this->toast()->error('Erro', 'Não é possível excluir. Existem candidatos associados.')->send();
            return;
        }

        $course->delete();

        $this->toast()->success('Sucesso', 'Curso excluído com sucesso!')->send();
    }

    public function save()
    {
        $this->validate();

        if ($this->editing && $this->courseId) {
            $course = Course::findOrFail($this->courseId);
            $course->update(['name' => $this->name]);

            $course->examSubjects()->delete();

            foreach ([$this->examSubject1, $this->examSubject2] as $subjectId) {
                if ($subjectId) {
                    $course->examSubjects()->create([
                        'uuid' => Str::uuid()->toString(),
                        'exam_subject_id' => $subjectId,
                    ]);
                }
            }

            $this->toast()->success('Curso atualizado com sucesso!', 'Atualização')->send();
        } else {
            $nextCode = Course::max('code') + 1;

            $course = Course::create([
                'name' => $this->name,
                'code' => $nextCode,
                'uuid' => Str::uuid()->toString(),
            ]);

            foreach ([$this->examSubject1, $this->examSubject2] as $subjectId) {
                if ($subjectId) {
                    $course->examSubjects()->create([
                        'uuid' => Str::uuid()->toString(),
                        'exam_subject_id' => $subjectId,
                    ]);
                }
            }

            $this->toast()->success('Curso adicionado com sucesso!', 'Novo curso')->send();
        }

        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editing = false;
        $this->courseId = null;
        $this->name = '';
        $this->examSubject1 = null;
        $this->examSubject2 = null;
    }

    public function render()
    {
        $courses = Course::with(['examSubjects.examSubject'])
            ->when($this->search, fn($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->paginate($this->quantity);

        return view('livewire.disciplines', [
            'rows' => $courses,
        ])->layout('layouts.admin');
    }
}
