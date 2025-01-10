<?php

namespace App\Livewire;

use App\Models\Monitor;
use App\Models\Province;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Monitors extends Component
{
    use Interactions;

    public $monitors;
    public $name;
    public $provinces;
    public $email;
    public $phone;
    public $status = true;
    public $editing = false;
    public $monitorId;
    public $province_id; // Propriedade para armazenar a província selecionada

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:monitors,email',
        'phone' => 'nullable|string|max:15',
        'status' => 'required|boolean',
        'province_id' => 'required|exists:provinces,id',
    ];

    public function mount()
    {
        $this->provinces = Province::all(); // Carrega todas as províncias
        $this->monitors = Monitor::all();   // Carrega todos os monitores
    }

    public function store()
    {
        if ($this->editing) {
            $this->rules['email'] = 'required|email|unique:monitors,email,' . $this->monitorId;
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'province_id' => $this->province_id,
            'status' => $this->status,
        ];

        if ($this->editing) {
            $monitor = Monitor::findOrFail($this->monitorId);
            $monitor->update($data);

            $this->toast()->success('Sucesso', 'Monitor atualizado com sucesso!')->send();
        } else {
            Monitor::create($data);

            $this->toast()->success('Sucesso', 'Monitor cadastrado com sucesso!')->send();
        }

        $this->resetForm();
        $this->monitors = Monitor::all(); // Atualiza a lista de monitores
    }

    public function edit($id)
    {
        $monitor = Monitor::findOrFail($id);

        $this->monitorId = $monitor->id;
        $this->name = $monitor->name;
        $this->email = $monitor->email;
        $this->phone = $monitor->phone;
        $this->status = $monitor->status;
        $this->province_id = $monitor->province_id; // Corrigido para carregar a província
        $this->editing = true;

        $this->rules['email'] = 'required|email|unique:monitors,email,' . $this->monitorId;
    }

    public function delete($id)
    {
        $monitor = Monitor::findOrFail($id);
        $monitor->delete();

        $this->toast()->success('Sucesso', 'Monitor deletado com sucesso!')->send();
        $this->monitors = Monitor::all(); // Atualiza a lista de monitores
    }

    public function resetForm()
    {
        $this->editing = false;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->status = true;
        $this->province_id = null; // Restaura a seleção de província

        // Restaura as regras de validação padrão
        $this->rules['email'] = 'required|email|unique:monitors,email';
    }

    public function render()
    {
        return view('livewire.monitors');
    }
}
