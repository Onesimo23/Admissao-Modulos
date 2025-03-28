<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class Disciplines extends Component
{
    use WithPagination;

    public $search = '';
    public $quantity = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingQuantity()
    {
        $this->resetPage();
    }

    public function render()
    {
        $courses = Course::with('disciplina')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->paginate($this->quantity);

        return view('livewire.disciplines', [
            'rows' => $courses,
        ])->layout('layouts.admin');
    }
}
