<?php

namespace App\Http\Controllers\Front;
// No topo do arquivo PostController.php
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Categoria;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class PostController extends Controller
{
    public function show($slug)
    {
        $post = Post::with(['categoria', 'user', 'tags', 'comments' => function($q) {
                    $q->where('aprovado', true)->latest();
                }])
                ->where('slug', $slug)
                ->where('status', 'publicado')
                ->where('publicado_em', '<=', now())
                ->firstOrFail();

        // Incrementar views
        $post->increment('views');

        // Posts relacionados
        $relacionados = Post::with('categoria')
                          ->where('categoria_id', $post->categoria_id)
                          ->where('id', '!=', $post->id)
                          ->where('status', 'publicado')
                          ->where('publicado_em', '<=', now())
                          ->latest('publicado_em')
                          ->take(3)
                          ->get();

        $categorias = Categoria::withCount('posts')->get();

        return view('front.post', compact('post', 'relacionados', 'categorias'));
    }

    public function storeComment(Request $request, $postId)
{
    // Validação server-side
    $validator = Validator::make($request->all(), [
        'nome' => 'required|string|max:255|min:3',
        'email' => 'required|email|max:255',
        'comentario' => 'required|string|min:5|max:1000',
    ], [
        'nome.required' => 'O nome é obrigatório.',
        'nome.min' => 'O nome deve ter no mínimo 3 caracteres.',
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Digite um email válido.',
        'comentario.required' => 'O comentário é obrigatório.',
        'comentario.min' => 'O comentário deve ter no mínimo 5 caracteres.',
        'comentario.max' => 'O comentário não pode ter mais de 1000 caracteres.',
    ]);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Por favor, corrija os erros abaixo.');
    }

    // Verificar se a coluna ip_address existe antes de usar
    try {
        $hasIpColumn = Schema::hasColumn('comments', 'ip_address');
        
        if ($hasIpColumn) {
            $lastComment = Comment::where('ip_address', $request->ip())
                                  ->where('created_at', '>=', now()->subMinutes(5))
                                  ->count();

            if ($lastComment >= 3) {
                return back()
                    ->withInput()
                    ->with('error', 'Você atingiu o limite de comentários. Aguarde alguns minutos para comentar novamente.');
            }
        }
    } catch (\Exception $e) {
        // Se a coluna não existir, apenas continua
        \Log::warning('Coluna ip_address não encontrada, pulando verificação anti-spam');
    }

    // Salvar comentário
    try {
        $data = [
            'post_id' => $postId,
            'nome' => strip_tags($request->nome),
            'email' => $request->email,
            'comentario' => strip_tags($request->comentario),
            'aprovado' => false,
        ];
        
        // Adicionar ip_address apenas se a coluna existir
        if (Schema::hasColumn('comments', 'ip_address')) {
            $data['ip_address'] = $request->ip();
        }
        
        // Adicionar user_agent apenas se a coluna existir
        if (Schema::hasColumn('comments', 'user_agent')) {
            $data['user_agent'] = $request->userAgent();
        }
        
        $comment = Comment::create($data);

        Log::info('Novo comentário aguardando aprovação', [
            'comment_id' => $comment->id,
            'post_id' => $postId,
            'ip' => $request->ip()
        ]);

        return back()->with('success', 'Comentário enviado com sucesso! Ele será publicado após aprovação do moderador.');

    } catch (\Exception $e) {
        Log::error('Erro ao salvar comentário: ' . $e->getMessage());
        return back()
            ->withInput()
            ->with('error', 'Ocorreu um erro ao enviar seu comentário. Tente novamente.');
    }
}
    
}