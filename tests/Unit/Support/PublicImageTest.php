<?php

namespace Tests\Unit\Support;

use App\Support\PublicImage;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class PublicImageTest extends TestCase
{
    public function test_missing_local_images_are_suppressed_before_rendering(): void
    {
        Storage::fake('public');

        $this->assertNull(PublicImage::url('/storage/uploads/shows/missing.jpg'));
        $this->assertNull(PublicImage::url('../private/secret.jpg'));
    }

    public function test_existing_local_and_remote_images_are_retained(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('uploads/shows/cover.jpg', 'image');

        $this->assertStringEndsWith(
            '/storage/uploads/shows/cover.jpg',
            PublicImage::url('/storage/uploads/shows/cover.jpg')
        );
        $this->assertSame(
            'https://images.example.com/cover.jpg',
            PublicImage::url('https://images.example.com/cover.jpg')
        );
    }
}
