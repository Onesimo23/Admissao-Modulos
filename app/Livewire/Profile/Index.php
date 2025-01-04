<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use Interactions, WithFileUploads;

    public $id;
    public $name;
    public $email;
    public $new_profile_photo;
    public $profile_photo;
    public $phone;
    public $username;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $activeTab = 'personal-data';
    public $showForm = 0;


    protected function rules()
    {
        return [
            'name'                     => 'required|string|max:255',
            'email'                     => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->id),
            ],
            // 'new_profile_photo'         => 'nullable|image|max:2048', // 2MB Max
            // 'phone'                    => 'nullable|string|max:20',
            'current_password'          => 'required_with:new_password|string|min:8',
            'new_password'             => 'nullable|string|min:8|confirmed',
        ];
    }

    public function mount()
    {
        $user = Auth::user();
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        // $this->profile_photo = $user->profile_photo;
        // $this->phone = $user->phone;
    }

    public function updatePersonalData()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->id),
            ],
            'username' => [
                'string',
                'max:25',
                Rule::unique('users')->ignore($this->id),
            ],
            // 'new_profile_photo' => 'nullable|image|max:2048', // 2MB Max
            // 'phone' => 'nullable|string|max:20',
        ]);

        $user = User::findOrFail($this->id);
        $user->name = $this->name;
        $user->email = $this->email;
        $this->username = $user->username;

        // $user->phone = $this->phone;

        // if ($this->new_profile_photo) {
        //     $photoPath = $this->new_profile_photo->store('profile-photos', 'public');
        //     $user->profile_photo = $photoPath;
        // }

        $user->save();

        // $this->reset('new_profile_photo');
        $this->resetValidation();
        $this->toast()->success('Sucesso', 'Perfil atualizado com sucesso!')->send();
    }


    public function updatePassword()
    {
        $validated = $this->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Senha atual está incorreta.');
            return;
        }

        $user->password = Hash::make($this->new_password);

        if ($user instanceof User) {
            $user->save();
        } else {
            $this->addError('general', 'Problema com a instância do usuário.');
            return;
        }

        $this->reset('current_password', 'new_password', 'new_password_confirmation');
        $this->toast()->success('Sucesso', 'Senha atualizada com sucesso!')->send();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.profile.profile-edit');
    }
}
