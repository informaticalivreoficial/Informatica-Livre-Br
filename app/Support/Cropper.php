<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Cropper
{
    public static function thumb(string $uri, int $width, ?int $height = null): string
    {
        if (empty($uri)) {
            return '';
        }

        $storage    = Storage::disk();
        $filename   = md5($uri) . '-' . $width . 'x' . ($height ?? 0) . '.webp';
        $cachedPath = 'cache/' . $filename;

        if ($storage->exists($cachedPath)) {
            return $cachedPath;
        }

        if (! $storage->exists($uri)) {
            return '';
        }

        $manager = new ImageManager(new Driver());
        $image   = $manager->read($storage->get($uri));

        if ($height) {
            $image->cover($width, $height);
        } else {
            $image->scale(width: $width);
        }

        $storage->put($cachedPath, (string) $image->toWebp(80));

        return $cachedPath;
    }

    public static function flush(?string $path = null): void
    {
        $storage = Storage::disk();
        $files   = $storage->files('cache');

        if (empty($files)) {
            return;
        }

        $prefix = $path ? md5($path) : null;

        foreach ($files as $file) {
            if ($prefix && ! str_starts_with(basename($file), $prefix)) {
                continue;
            }

            $storage->delete($file);
        }
    }
}
