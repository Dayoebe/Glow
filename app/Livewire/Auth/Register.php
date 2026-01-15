<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.app')]
#[Title('Register - Glow FM Radio')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'name.min' => 'Name must be at least 3 characters',
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email is already registered',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 6 characters',
        'password.confirmed' => 'Passwords do not match',
    ];

    public function register()
    {
        // Validate input
        $validated = $this->validate();

        // Create new user
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'user',
            'is_active' => true,
        ]);

        // Log the user in
        Auth::login($user, true);

        // Regenerate session
        session()->regenerate();

        // Redirect to dashboard
        return $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}