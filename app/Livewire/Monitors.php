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
        'email' => 'required|email|unique:monitors,email|regex:/^[\w\.-]+@unisave\.ac\.mz$/',
        'phone' => 'nullable|string|max:15',
        'status' => 'required|boolean',
        'school_id' => 'required|exists:schools,id',
        'room_id' => 'required|exists:class_models,id',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->monitors = Monitor::with(['room', 'school'])->get(); 
        $this->schools = School::all(); 
        $this->rooms = collect(); 
    }

    public function updatedSchoolId($value)
    {
        $this->rooms = collect(); 
    
        if ($value) {
            $this->rooms = ClassModel::where('school_id', $value)
                ->where('status', true)
                ->get();
        }
    }

    public function store()
    {
        $rules = $this->rules;
     
        if ($this->editing) {
            $rules['email'] = 'required|email|regex:/^[\w\.-]+@unisave\.ac\.mz$/|unique:monitors,email,' . $this->monitorId;
        }
    
        $this->validate($rules);
    
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
        $this->loadData();
    }
    

    public function edit($id)
    {
        $monitor = Monitor::findOrFail($id);

        $this->editing = true;
        $this->monitorId = $monitor->id;
        $this->name = $monitor->name;
        $this->email = $monitor->email;
        $this->phone = $monitor->phone;
        $this->status = $monitor->status;
        $this->school_id = $monitor->school_id;

        // Carregar salas associadas à escola
        $this->rooms = ClassModel::where('school_id', $monitor->school_id)
            ->where('status', true)
            ->get();
        $this->room_id = $monitor->room_id;
    }

    public function delete($id)
    {
        $monitor = Monitor::findOrFail($id);

        try {
            $monitor->delete();
            $this->toast()->success('Sucesso', 'Monitor excluído com sucesso!')->send();
        } catch (\Exception $e) {
            $this->toast()->error('Erro', 'Não foi possível excluir o monitor.')->send();
        }

        $this->loadData();
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
        return view('livewire.monitors')->withErrors(session()->get('errors'));
    }
}
