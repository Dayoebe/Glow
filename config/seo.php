<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Canonical public origin
    |--------------------------------------------------------------------------
    |
    | Search engines should see one HTTPS origin everywhere: HTML canonicals,
    | structured data, feeds, sitemaps and crawler guidance. Keep this value
    | independent from APP_URL so local and preview requests cannot leak their
    | hostnames into public discovery documents.
    |
    */
    'canonical_url' => rtrim(
        env('SEO_CANONICAL_URL', 'https://www.glowfmradio.com'),
        '/'
    ),

    /*
    | Redirect alternate hosts/schemes only in production. Apache also performs
    | the redirect for static files before Laravel is reached.
    */
    'enforce_canonical' => env('SEO_ENFORCE_CANONICAL', true),

    /*
    | Public pages can be quoted fully in search and AI-generated previews.
    | Private, account, preview and internal-search pages are handled by the
    | central SEO helper and response middleware.
    */
    'public_robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
    'private_robots' => 'noindex, nofollow, noarchive, nosnippet, noimageindex',
    'filtered_robots' => 'noindex, follow, noarchive',
];
