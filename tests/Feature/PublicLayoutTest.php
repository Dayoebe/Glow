<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class PublicLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_public_page_layout_emits_a_share_ready_image(): void
    {
        $html = view('layouts.app', [
            'slot' => new HtmlString(''),
            'errors' => new ViewErrorBag,
            'meta_title' => 'Heart to Heart',
            'meta_image' => 'https://res.cloudinary.com/demo/image/upload/v123/podcasts/heart-to-heart.png',
        ])->render();

        $socialImage = 'https://res.cloudinary.com/demo/image/upload/f_jpg,q_auto:good,c_fill,g_auto,w_1200,h_630/v123/podcasts/heart-to-heart.png';

        $this->assertStringContainsString('<meta property="og:image" content="'.$socialImage.'">', $html);
        $this->assertStringContainsString('<meta property="og:image:width" content="1200">', $html);
        $this->assertStringContainsString('<meta property="og:image:height" content="630">', $html);
        $this->assertStringContainsString('<meta name="twitter:image" content="'.$socialImage.'">', $html);
    }

    public function test_google_site_tags_are_not_loaded_on_localhost(): void
    {
        config()->set('services.google_site_tags.enabled', true);

        $response = $this->get('http://localhost/team');

        $response
            ->assertOk()
            ->assertDontSee('pagead2.googlesyndication.com', false)
            ->assertDontSee('googletagmanager.com', false)
            ->assertDontSee('google-adsense-account', false);
    }

    public function test_google_site_tags_can_be_loaded_on_the_public_domain(): void
    {
        config()->set('services.google_site_tags.enabled', true);

        $response = $this->get('https://glowfmradio.com/team');

        $response
            ->assertOk()
            ->assertSee('pagead2.googlesyndication.com', false)
            ->assertSee('googletagmanager.com', false)
            ->assertSee('google-adsense-account', false);
    }

    public function test_livewire_navigation_omits_only_the_redundant_css_preload(): void
    {
        $response = $this
            ->withHeader('X-Livewire-Navigate', '1')
            ->get('http://localhost/team');

        $response
            ->assertOk()
            ->assertDontSee('rel="preload" as="style"', false)
            ->assertSee('rel="modulepreload" as="script"', false)
            ->assertSee('rel="stylesheet"', false)
            ->assertSee('data-navigate-track="reload"', false);
    }

    public function test_a_hard_load_keeps_the_css_preload(): void
    {
        $this->get('http://localhost/team')
            ->assertOk()
            ->assertSee('rel="preload" as="style"', false);
    }

    public function test_redirects_do_not_emit_a_noindex_header(): void
    {
        $this->get('/programs')
            ->assertRedirect('/shows')
            ->assertHeaderMissing('X-Robots-Tag');
    }

    public function test_not_found_pages_emit_noindex_metadata(): void
    {
        $this->get('/this-page-does-not-exist')
            ->assertNotFound()
            ->assertSee('<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">', false)
            ->assertSee('<meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">', false);
    }
}
