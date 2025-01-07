<?php

namespace App\Livewire;

use App\Models\Monitor;
use App\Models\School;
use App\Models\ClassModel;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Monitors extends Component
{
    use Interactions;

    public $monitors;
    public $schools;
    public $rooms = []; 
    public $name;
    public $email;
    public $phone;
    public $status = true;
    public $school_id;
    public $room_id; 
    public $editing = false;
    public $monitorId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:monitors,email',
        'phone' => 'nullable|string|max:15',
        'status' => 'required|boolean',
        'school_id' => 'required|exists:schools,id',
        'room_id' => 'required|exists:class_models,id',
    ];

    public function mount()
    {
        $this->monitors = Monitor::with('room', 'room.school')->get(); 
        $this->schools = School::all(); 
        $this->rooms = collect(); 
    }

    public function updatedSchoolId($value)
    {
        if ($value) {
            // Carregar salas associadas à escola selecionada
            $this->rooms = ClassModel::where('school_id', $value)
                ->where('status', true)
                ->get();
        } else {
            $this->rooms = collect();
        }
        $this->room_id = null; // Resetar a sala selecionada
    }

    public function store()
    {
        $this->validate();

        if ($this->editing) {
            $monitor = Monitor::findOrFail($this->monitorId);
            $monitor->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'status' => $this->status,
                'school_id' => $this->school_id,
                'room_id' => $this->room_id,
            ]);

            $this->toast()->success('Sucesso', 'Monitor atualizado com sucesso!')->send();
        } else {
            Monitor::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'status' => $this->status,
                'school_id' => $this->school_id,
                'room_id' => $this->room_id,
            ]);

            $this->toast()->success('Sucesso', 'Monitor cadastrado com sucesso!')->send();
        }

        $this->resetForm();
        $this->monitors = Monitor::with('room', 'room.school')->get();
    }

    public function resetForm()
    {
        $this->editing = false;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->status = true;
        $this->school_id = null;
        $this->room_id = null;
        $this->rooms = collect();
    }

    public function render()
    {
        return view('livewire.monitors');
    }
}
