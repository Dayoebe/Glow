<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

final class PublicImage
{
    public static function url(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        if (Str::startsWith($value, ['https://', 'http://', '//', 'data:', 'blob:'])) {
            return $value;
        }

        $path = rawurldecode((string) parse_url($value, PHP_URL_PATH));
        $path = ltrim(str_replace('\\', '/', $path), '/');

        if ($path === '' || Str::contains($path, ["\0", '../'])) {
            return null;
        }

        try {
            $exists = Str::startsWith($path, 'storage/')
                ? Storage::disk('public')->exists(Str::after($path, 'storage/'))
                : is_file(public_path($path));
        } catch (Throwable) {
            return null;
        }

        return $exists ? asset($path) : null;
    }
}
