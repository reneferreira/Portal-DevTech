<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id', 
        'nome', 
        'email', 
        'comentario', 
        'aprovado',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'aprovado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relacionamento com o post
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // Escopo para comentários aprovados
    public function scopeAprovado($query)
    {
        return $query->where('aprovado', true);
    }

    // Escopo para comentários pendentes
    public function scopePendente($query)
    {
        return $query->where('aprovado', false);
    }

    // Formatar data
    public function getDataFormatadaAttribute()
    {
        return $this->created_at->format('d/m/Y \à\s H:i');
    }

    // Verificar se é novo (últimas 24h)
    public function getIsNovoAttribute()
    {
        return $this->created_at->diffInHours(now()) < 24;
    }
}