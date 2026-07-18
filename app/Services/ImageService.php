<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Throwable;

/**
 * Handles image uploads: downscales oversized images and re-encodes to WebP so
 * product grids and banners ship small, optimised assets instead of raw phone photos.
 * Falls back to a plain store() if image processing is unavailable.
 */
class ImageService
{
    public function store(UploadedFile $file, string $folder, int $maxWidth = 1600, int $quality = 82): string
    {
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());

            if ($image->width() > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }

            $path = trim($folder, '/') . '/' . Str::uuid()->toString() . '.webp';
            Storage::disk('public')->put($path, (string) $image->toWebp($quality));

            return $path;
        } catch (Throwable $e) {
            // GD/Imagick missing or an unreadable upload — never lose the image, store raw.
            Log::warning('ImageService optimisation failed, storing raw: ' . $e->getMessage());

            return $file->store($folder, 'public');
        }
    }
}
