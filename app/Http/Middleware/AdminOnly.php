<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || (method_exists($user, 'isAccessDisabled') && $user->isAccessDisabled())) {
            return redirect()->route('home')->with('error', 'Your dashboard access has been disabled.');
        }

        $isAdmin = $user && (method_exists($user, 'hasRole') ? $user->hasRole('admin') : $user->isAdmin());

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'You do not have access to the admin dashboard.');
        }

        return $next($request);
    }
}
