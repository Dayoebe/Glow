<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
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

        if ((method_exists($user, 'isAccessDisabled') && $user->isAccessDisabled())
            || (isset($user->is_active) && !$user->is_active)) {
            return response()->json(['message' => 'Account disabled.'], 403);
        }

        $allowedRoles = ['admin', 'staff', 'corp_member', 'intern'];
        $isAllowed = (
            (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($allowedRoles))
            || (method_exists($user, 'isAdmin') && method_exists($user, 'isStaff') && ($user->isAdmin() || $user->isStaff()))
        );

        if (!$isAllowed) {
            return response()->json(['message' => 'You do not have access to the admin dashboard.'], 403);
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
