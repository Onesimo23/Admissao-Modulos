<?php

namespace App\Livewire;

use App\Models\Monitor;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Monitors extends Component
{
    use Interactions;

    public $monitors;
    public $name;
    public $email;
    public $phone;
    public $status = true;
    public $editing = false;
    public $monitorId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:monitors,email',
        'phone' => 'nullable|string|max:15',
        'status' => 'required|boolean',
    ];

    public function mount()
    {
        $this->monitors = Monitor::all();
    }

    public function store()
    {
        if ($this->editing) {
            $this->rules['email'] = 'required|email|unique:monitors,email,' . $this->monitorId;
        }

        $this->validate();

        if ($this->editing) {
            $monitor = Monitor::findOrFail($this->monitorId);
            $monitor->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'status' => $this->status,
            ]);

            $this->toast()->success('Sucesso', 'Monitor atualizado com sucesso!')->send();
        } else {
            Monitor::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'status' => $this->status,
            ]);

            $this->toast()->success('Sucesso', 'Monitor cadastrado com sucesso!')->send();
        }

        $this->resetForm();
        $this->monitors = Monitor::all();
    }

    public function edit($id)
    {
        $monitor = Monitor::findOrFail($id);
        $this->monitorId = $monitor->id;
        $this->name = $monitor->name;
        $this->email = $monitor->email;
        $this->phone = $monitor->phone;
        $this->status = $monitor->status;
        $this->editing = true;
        $this->rules['email'] = 'required|email|unique:monitors,email,' . $this->monitorId;
    }

    public function delete($id)
    {
        $monitor = Monitor::findOrFail($id);
        $monitor->delete();

        $this->toast()->success('Sucesso', 'Monitor deletado com sucesso!')->send();
        $this->monitors = Monitor::all();
    }

    public function resetForm()
    {
        $this->editing = false;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->status = true;

        // Restaure as regras de validação padrão
        $this->rules['email'] = 'required|email|unique:monitors,email';
    }

    public function render()
    {
        return view('livewire.monitors');
    }
}
