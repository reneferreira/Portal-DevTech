<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'slug', 'descricao', 'imagem', 'link', 
        'posicao', 'tipo', 'html_code', 'ativo', 'ordem',
        'data_inicio', 'data_fim', 'clicks', 'visualizacoes'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'clicks' => 'integer',
        'visualizacoes' => 'integer'
    ];

    // Verificar se banner está ativo e dentro do período
    public function isActive()
    {
        if (!$this->ativo) return false;
        
        $hoje = now()->startOfDay();
        
        if ($this->data_inicio && $hoje < $this->data_inicio) return false;
        if ($this->data_fim && $hoje > $this->data_fim) return false;
        
        return true;
    }

    // Acessor para URL da imagem
    public function getImagemUrlAttribute()
    {
        if ($this->imagem && Storage::disk('public')->exists($this->imagem)) {
            return Storage::url($this->imagem);
        }
        return null;
    }

    // Incrementar clicks
    public function incrementClicks()
    {
        $this->increment('clicks');
    }

    // Incrementar visualizações
    public function incrementVisualizacoes()
    {
        $this->increment('visualizacoes');
    }
}