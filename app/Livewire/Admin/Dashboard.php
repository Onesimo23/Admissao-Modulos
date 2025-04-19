<?php

namespace App\Livewire\Admin;

use App\Models\Candidate;
use App\Models\Course;
use App\Models\User;
use App\Models\Payment; 
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class Dashboard extends Component
{
    use WithPagination; 



    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.admin');
    }
}
