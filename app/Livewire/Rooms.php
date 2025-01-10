<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\School;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;
use Illuminate\Database\Eloquent\Builder;

class Rooms extends Component
{
    use Interactions, WithPagination;

    public $schools;
    public $name;
    public $capacity;
    public $status = true;
    public $school_id;
    public $editing = false;
    public $classModelId;
    public $priority_level;
    public ?int $quantity = 10;
    public ?string $search = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
        'status' => 'required|boolean',
        'priority_level' => 'required|integer|min:1',
        'school_id' => 'required|exists:schools,id',
    ];

    public function mount()
    {
        $this->schools = School::all();
    }

    public function updatingQuantity()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function store()
    {
        $this->validate();

        if ($this->editing) {
            $classModel = Room::findOrFail($this->classModelId);
            $classModel->update([
                'name' => $this->name,
                'capacity' => $this->capacity,
                'status' => $this->status,
                'priority_level' => $this->priority_level,
                'school_id' => $this->school_id,
            ]);
            $this->toast()->success('Sucesso', 'Sala atualizada com sucesso!')->send();
        } else {
            Room::create([
                'name' => $this->name,
                'capacity' => $this->capacity,
                'status' => $this->status,
                'priority_level' => $this->priority_level,
                'school_id' => $this->school_id,
            ]);
            $this->toast()->success('Sucesso', 'Sala criada com sucesso!')->send();
        }

        $this->resetForm();
    }

    public function edit($id)
    {
        $this->editing = true;
        $classModel = Room::findOrFail($id);
        $this->classModelId = $id;
        $this->name = $classModel->name;
        $this->capacity = $classModel->capacity;
        $this->status = $classModel->status;
        $this->priority_level = $classModel->priority_level;
        $this->school_id = $classModel->school_id;
    }

    public function delete($id)
    {
        Room::findOrFail($id)->delete();
        $this->toast()->success('Sucesso', 'Sala excluída com sucesso!')->send();
    }

    private function resetForm()
    {
        $this->editing = false;
        $this->name = '';
        $this->capacity = '';
        $this->status = true;
        $this->priority_level = '';
        $this->school_id = '';
    }

    public function render()
    {
        $rooms = Room::query()
            ->with('school')
            ->when($this->search, function (Builder $query) {
                return $query->where('name', 'like', "%{$this->search}%");
            })
            ->paginate($this->quantity);

        return view('livewire.class-models', [
            'headers' => [
                ['index' => 'id', 'label' => '#'],
                ['index' => 'name', 'label' => 'Nome'],
                ['index' => 'capacity', 'label' => 'Capacidade'],
                ['index' => 'status', 'label' => 'Status'],
                ['index' => 'school.name', 'label' => 'Escola'],
                ['index' => 'priority_level', 'label' => 'Nível de Prioridade'],
            ],
            'rows' => $rooms,
        ]);
    }
}

