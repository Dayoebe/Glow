<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminOrStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $allowedRoles = ['admin', 'staff', 'corp_member', 'intern'];

        if (!$user || (method_exists($user, 'isAccessDisabled') && $user->isAccessDisabled())) {
            return response()->json(['message' => 'Account disabled.'], 403);
        }

        $isAllowed = $user && (
            (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($allowedRoles))
            || (method_exists($user, 'isAdmin') && method_exists($user, 'isStaff') && ($user->isAdmin() || $user->isStaff()))
        );

        if (!$isAllowed) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
