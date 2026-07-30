<?php

namespace App\Http\Middleware;

use App\Support\Seo;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceCanonicalUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            !app()->environment('production')
            || !config('seo.enforce_canonical')
            || !$request->isMethodSafe()
        ) {
            return $next($request);
        }

        $canonical = parse_url(Seo::siteUrl());
        $canonicalHost = strtolower((string) ($canonical['host'] ?? ''));
        $canonicalScheme = strtolower((string) ($canonical['scheme'] ?? 'https'));
        $forwardedScheme = strtolower(trim(explode(',', (string) $request->header('X-Forwarded-Proto'))[0]));
        $requestScheme = $forwardedScheme !== '' ? $forwardedScheme : $request->getScheme();

        if (
            strtolower($request->getHost()) === $canonicalHost
            && $requestScheme === $canonicalScheme
        ) {
            return $next($request);
        }

        $path = '/' . ltrim($request->path(), '/');
        if ($path === '//') {
            $path = '/';
        }

        return redirect()->away(Seo::canonicalUrl($path, $request->query()), 301);
    }
}
