<?php

namespace Tests\Unit\Support;

use App\Support\Seo;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SeoTest extends TestCase
{
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
