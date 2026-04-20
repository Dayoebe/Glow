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
        $allowedRoles = ['admin', 'staff', 'corp_member', 'intern'];

        if (!$user || (method_exists($user, 'isAccessDisabled') && $user->isAccessDisabled())) {
            return redirect()->route('home')->with('error', 'Your dashboard access has been disabled.');
        }

        $isAllowed = $user && (
            (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($allowedRoles))
            || (method_exists($user, 'isAdmin') && method_exists($user, 'isStaff') && ($user->isAdmin() || $user->isStaff()))
        );

        if (!$isAllowed) {
            return redirect()->route('home')->with('error', 'You do not have access to the admin dashboard.');
        }

        return $next($request);
    }
}
