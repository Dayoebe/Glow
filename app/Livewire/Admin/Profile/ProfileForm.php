<?php

namespace App\Livewire\Admin\Profile;

use App\Support\PersonProfileSync;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ProfileForm extends Component
{
    public $name = '';
    public $email = '';
    public $avatar = '';
    public $password = '';
    public $password_confirmation = '';

    protected function rules()
    {
        $userId = auth()->id();

        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'avatar' => 'nullable|url|max:500',
            'password' => 'nullable|min:6|confirmed',
        ];
    }

    public function mount()
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatar = $user->avatar ?? '';
    }

    public function save()
    {
        $this->validate();

        $user = auth()->user();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar ?: null,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);
        PersonProfileSync::fromUser($user);

        $this->reset('password', 'password_confirmation');

        session()->flash('success', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.profile.form')
            ->layout('layouts.admin', [
                'header' => 'My Profile',
            ]);
    }
}
