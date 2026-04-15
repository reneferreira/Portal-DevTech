<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'slug', 'resumo', 'conteudo', 'imagem', 'imagem_thumbnail',
        'categoria_id', 'user_id', 'views', 'publicado_em', 'status', 'destaque'
    ];

    protected $casts = [
        'publicado_em' => 'datetime',
        'destaque' => 'boolean'
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('aprovado', true);
    }

    public function scopePublicado($query)
    {
        return $query->where('status', 'publicado')
                     ->where('publicado_em', '<=', now());
    }

    public function scopeDestaque($query)
    {
        return $query->where('destaque', true);
    }
}