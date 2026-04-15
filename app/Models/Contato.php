<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contato extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'email', 'telefone', 'assunto', 'mensagem', 'post_id', 'status'];

    protected $casts = [
        'status' => 'string'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}