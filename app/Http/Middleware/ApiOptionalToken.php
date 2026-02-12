<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiOptionalToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return $next($request);
        }

        $hashed = hash('sha256', $token);
        $user = User::where('api_token', $hashed)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        if (isset($user->is_active) && !$user->is_active) {
            return response()->json(['message' => 'Account disabled.'], 403);
        }

        $request->setUserResolver(fn () => $user);
        auth()->setUser($user);

        return $next($request);
    }
}
