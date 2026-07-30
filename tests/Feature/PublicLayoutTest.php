<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLayoutTest extends TestCase
{
    use RefreshDatabase;

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
}
