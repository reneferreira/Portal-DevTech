<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'slug', 'descricao', 'youtube_id', 'thumbnail',
        'views', 'destaque', 'status', 'categoria_id', 'user_id', 'publicado_em'
    ];

    protected $casts = [
        'destaque' => 'boolean',
        'publicado_em' => 'datetime',
        'views' => 'integer'
    ];

    // Gerar slug automaticamente
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($video) {
            $video->slug = Str::slug($video->titulo);
        });
    }

    // Relacionamentos
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePublicado($query)
    {
        return $query->where('status', 'publicado')
                     ->where('publicado_em', '<=', now());
    }

    public function scopeDestaque($query)
    {
        return $query->where('destaque', true);
    }

    // Acessor para URL do YouTube
    public function getYoutubeUrlAttribute()
    {
        return "https://www.youtube.com/watch?v={$this->youtube_id}";
    }

    public function getEmbedUrlAttribute()
    {
        return "https://www.youtube.com/embed/{$this->youtube_id}";
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg";
    }
}