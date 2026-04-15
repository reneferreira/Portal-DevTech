<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image URL or default placeholder
     */
    public static function getImageUrl($imagePath, $default = 'images/placeholder.jpg')
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            return Storage::url($imagePath);
        }
        
        return asset($default);
    }
    
    /**
     * Get thumbnail URL
     */
    public static function getThumbnailUrl($post, $default = 'images/placeholder.jpg')
    {
        // Primeiro tenta usar thumbnail específica
        if ($post->imagem_thumbnail && Storage::disk('public')->exists($post->imagem_thumbnail)) {
            return Storage::url($post->imagem_thumbnail);
        }
        
        // Depois tenta usar imagem principal
        if ($post->imagem && Storage::disk('public')->exists($post->imagem)) {
            return Storage::url($post->imagem);
        }
        
        return asset($default);
    }
}