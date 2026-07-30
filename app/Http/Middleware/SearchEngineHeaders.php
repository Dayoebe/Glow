<?php

namespace App\Http\Middleware;

use App\Support\Seo;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchEngineHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $contentType = strtolower((string) $response->headers->get('Content-Type'));

        if (
            $request->isMethodSafe()
            && ($contentType === '' || str_contains($contentType, 'text/html'))
        ) {
            $response->headers->set(
                'X-Robots-Tag',
                Seo::robotsDirectives($request, $response->getStatusCode())
            );
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Content-Language', 'en-NG');

        return $response;
    }
}
