<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $isAllowed = $user && (
            (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('staff')))
            || (method_exists($user, 'isAdmin') && method_exists($user, 'isStaff') && ($user->isAdmin() || $user->isStaff()))
        );

        if (!$isAllowed) {
            return redirect()->route('home')->with('error', 'You do not have access to the admin dashboard.');
        }

        return $next($request);
    }
}
