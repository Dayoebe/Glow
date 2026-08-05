<?php

namespace Tests\Unit\News;

use App\Livewire\Page\NewsDetail;
use App\Models\News\News;
use Tests\TestCase;

class NewsShareLinksTest extends TestCase
{
    public function test_social_share_links_include_the_article_and_use_secure_share_endpoints(): void
    {
        $component = new NewsDetail();
        $component->news = new News([
            'title' => 'Glow FM launches a new community programme',
            'excerpt' => 'The programme connects listeners with local opportunities.',
        ]);
        $component->shareUrl = 'https://glowfm.com/news/community-programme';

        $links = $component->getShareLinksProperty();

        $this->assertSame(
            'www.facebook.com',
            parse_url($links['facebook'], PHP_URL_HOST)
        );
        $this->assertStringContainsString(rawurlencode($component->news->title), $links['facebook']);
        $this->assertStringContainsString(rawurlencode($component->news->excerpt), $links['facebook']);
        $this->assertStringContainsString(rawurlencode($component->shareUrl), $links['facebook']);

        foreach (['x', 'facebook', 'linkedin', 'whatsapp', 'telegram', 'reddit'] as $platform) {
            $this->assertStringStartsWith('https://', $links[$platform]);
        }
    }
}
