<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image URL or default placeholder.
     */
    public static function getImageUrl($imagePath, $default = 'icons/icon-512.png')
    {
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        $disk = config('filesystems.media_disk', 'public');

        if ($imagePath && Storage::disk($disk)->exists($imagePath)) {
            return Storage::disk($disk)->url($imagePath);
        }

        return asset($default);
    }

    /**
     * Get thumbnail URL.
     */
    public static function getThumbnailUrl($post, $default = 'icons/icon-512.png')
    {
        $disk = config('filesystems.media_disk', 'public');

        if (filter_var($post->imagem_thumbnail, FILTER_VALIDATE_URL)) {
            return $post->imagem_thumbnail;
        }

        if ($post->imagem_thumbnail && Storage::disk($disk)->exists($post->imagem_thumbnail)) {
            return Storage::disk($disk)->url($post->imagem_thumbnail);
        }

        if (filter_var($post->imagem, FILTER_VALIDATE_URL)) {
            return $post->imagem;
        }

        if ($post->imagem && Storage::disk($disk)->exists($post->imagem)) {
            return Storage::disk($disk)->url($post->imagem);
        }

        return asset($default);
    }
}
