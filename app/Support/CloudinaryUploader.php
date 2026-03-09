<?php

namespace App\Support;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CloudinaryUploader
{
    public static function uploadImage($file, string $folder): string
    {
        if (!$file) {
            return '';
        }

        if (!self::syncCloudinaryConfig()) {
            return self::storeLocally($file, $folder);
        }

        try {
            $path = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folder,
                'resource_type' => 'image',
            ])->getSecurePath();

            if (!$path) {
                return self::storeLocally($file, $folder);
            }

            return $path;
        } catch (Throwable $exception) {
            report($exception);
            return self::storeLocally($file, $folder);
        }
    }

    private static function syncCloudinaryConfig(): bool
    {
        $cloudUrl = self::resolveCloudinaryUrl();

        if (!$cloudUrl) {
            return false;
        }

        if (!config('cloudinary.cloud_url')) {
            config([
                'cloudinary.cloud_url' => $cloudUrl,
                'cloudinary.upload_preset' => config('cloudinary.upload_preset'),
                'cloudinary.upload_route' => config('cloudinary.upload_route'),
                'cloudinary.upload_action' => config('cloudinary.upload_action'),
                'cloudinary.notification_url' => config('cloudinary.notification_url'),
            ]);
        }

        return true;
    }

    private static function resolveCloudinaryUrl(): ?string
    {
        $cloudUrl = config('cloudinary.cloud_url') ?: config('services.cloudinary.url');

        if (!empty($cloudUrl)) {
            return $cloudUrl;
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $key = config('services.cloudinary.key');
        $secret = config('services.cloudinary.secret');

        if ($cloudName && $key && $secret) {
            return sprintf('cloudinary://%s:%s@%s', $key, $secret, $cloudName);
        }

        return null;
    }

    private static function storeLocally($file, string $folder): string
    {
        $cleanFolder = trim($folder, '/');
        $path = $file->store('uploads/' . $cleanFolder, 'public');

        return Storage::url($path);
    }
}
