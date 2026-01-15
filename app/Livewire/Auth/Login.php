<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
#[Title('Login - Glow FM Radio')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    protected $messages = [
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 6 characters',
    ];

    public function login()
    {
        // Validate input
        $this->validate();

        // Attempt to authenticate
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true], $this->remember)) {
            // Regenerate session to prevent fixation attacks
            session()->regenerate();
            
            // Redirect to intended page or dashboard
            return $this->redirect(route('dashboard'), navigate: true);
        }

        // Authentication failed - add error
        $this->addError('email', 'These credentials do not match our records or your account is inactive.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}