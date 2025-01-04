<?php
// app/Http/Livewire/Index.php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class CandidateDetails extends Component
{
    public $showDetailsForm = false;
    public $candidate;

    public function mount()
    {
        $this->candidate = Auth::user()->candidate;
    }

    public function showDetails()
    {
        $this->showDetailsForm = true;
    }

    public function cancel()
    {
        $this->showDetailsForm = false;
    }

    // ... other methods
	 public function render()
    {
        return view('dashboard');
    }
}