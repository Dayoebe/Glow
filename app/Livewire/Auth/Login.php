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
    public $email = '';
    public $password = '';
    public $remember = false;

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
        $this->normalizeInputs();
        // Validate input
        $this->validate();

        // Attempt to authenticate
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true], $this->remember)) {
            // Regenerate session to prevent fixation attacks
            session()->regenerate();
            
            $user = Auth::user();
            if ($user && ($user->isAdmin() || $user->isStaff())) {
                // Redirect staff/admin to intended page or dashboard
                return $this->redirect(route('dashboard'), navigate: true);
            }

            // Regular users should not inherit stale intended admin URLs
            session()->forget('url.intended');
            return $this->redirect(route('home'), navigate: true);
        }

        // Authentication failed - add error
        $this->addError('email', 'These credentials do not match our records or your account is inactive.');
    }

    public function updatedEmail($value): void
    {
        $this->email = $this->normalizeScalar($value);
    }

    public function updatedPassword($value): void
    {
        $this->password = $this->normalizeScalar($value);
    }

    public function updatedRemember($value): void
    {
        $this->remember = is_array($value) ? (bool) ($value[0] ?? false) : (bool) $value;
    }

    private function normalizeInputs(): void
    {
        $this->email = $this->normalizeScalar($this->email);
        $this->password = $this->normalizeScalar($this->password);
        $this->remember = is_array($this->remember) ? (bool) ($this->remember[0] ?? false) : (bool) $this->remember;
    }

    private function normalizeScalar($value): string
    {
        if (is_array($value)) {
            return (string) ($value[0] ?? '');
        }

        return (string) $value;
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
