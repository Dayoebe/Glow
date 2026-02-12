<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PublicAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'is_active' => true,
        ]);

        $plainToken = bin2hex(random_bytes(40));
        $user->forceFill([
            'api_token' => hash('sha256', $plainToken),
            'api_token_created_at' => now(),
        ])->save();

        return response()->json([
            'token' => $plainToken,
            'user' => $this->formatUser($user),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (isset($user->is_active) && !$user->is_active) {
            return response()->json(['message' => 'Account disabled.'], 403);
        }

        $plainToken = bin2hex(random_bytes(40));
        $user->forceFill([
            'api_token' => hash('sha256', $plainToken),
            'api_token_created_at' => now(),
        ])->save();

        return response()->json([
            'token' => $plainToken,
            'user' => $this->formatUser($user),
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $this->formatUser($user),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->forceFill([
                'api_token' => null,
                'api_token_created_at' => null,
            ])->save();
        }

        return response()->json(['message' => 'Logged out.']);
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'role_label' => $user->role_label ?? null,
        ];
    }
}
