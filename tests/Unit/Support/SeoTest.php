<?php

namespace Tests\Unit\Support;

use App\Support\Seo;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SeoTest extends TestCase
{
    public function test_it_builds_a_share_ready_cloudinary_image(): void
    {
        $url = Seo::socialImageUrl(
            'https://res.cloudinary.com/demo/image/upload/v123/podcasts/cover.png'
        );

        $this->assertSame(
            'https://res.cloudinary.com/demo/image/upload/f_jpg,q_auto:good,c_fill,g_auto,w_1200,h_630/v123/podcasts/cover.png',
            $url
        );
    }

    public function test_it_uses_the_fallback_when_a_local_page_image_is_missing(): void
    {
        Storage::fake('public');

        $this->assertSame(
            Seo::absoluteUrl('/glowfm logo.jpeg'),
            Seo::socialImageUrl('/storage/missing-cover.jpg', '/glowfm logo.jpeg')
        );
    }

    #[DataProvider('videoUrls')]
    public function test_it_normalizes_video_embed_urls(?string $input, ?string $expected): void
    {
        $this->assertSame($expected, Seo::videoEmbedUrl($input));
    }

    public static function videoUrls(): array
    {
        return [
            'null' => [null, null],
            'blank' => ['   ', null],
            'youtube watch URL' => [
                'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'https://www.youtube.com/embed/dQw4w9WgXcQ',
            ],
            'direct video URL' => [
                'https://cdn.example.com/video.mp4',
                'https://cdn.example.com/video.mp4',
            ],
        ];
    }
}
