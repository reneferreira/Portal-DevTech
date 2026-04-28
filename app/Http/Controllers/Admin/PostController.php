<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Categoria;
use App\Models\Tag;
use App\Services\WebPushService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['categoria', 'user'])
                     ->latest()
                     ->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categorias', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'conteudo' => 'required|string',
            'resumo' => 'nullable|string|max:500',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:rascunho,publicado',
            'destaque' => 'boolean',
            'tags' => 'array|exists:tags,id'
        ]);

        // Processar imagem
        $imagemPath = null;
        $thumbnailPath = null;
        
        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $imagemPath = $imagem->store('posts', 'public');
            
            // Criar thumbnail (opcional)
            $thumbnailPath = $imagemPath;
        }

        // Criar slug único
        $slug = Str::slug($request->titulo);
        $count = Post::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $post = Post::create([
            'titulo' => $request->titulo,
            'slug' => $slug,
            'resumo' => $request->resumo,
            'conteudo' => $request->conteudo,
            'imagem' => $imagemPath,
            'imagem_thumbnail' => $thumbnailPath,
            'categoria_id' => $request->categoria_id,
            'user_id' => auth()->id(),
            'status' => $request->status,
            'destaque' => $request->has('destaque'),
            'publicado_em' => $request->status == 'publicado' ? now() : null
        ]);

        // Associar tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        if ($post->status === 'publicado') {
            app(WebPushService::class)->sendToAll([
                'title' => 'Novo post no Portal DevTech',
                'body' => Str::limit($post->resumo ?: $post->titulo, 140),
                'url' => route('post', $post->slug),
                'icon' => asset('icons/icon-192.png'),
                'badge' => asset('icons/badge-96.png'),
            ]);
        }

        return redirect()->route('admin.posts.index')
                         ->with('success', 'Post criado com sucesso!');
    }

    public function edit(Post $post)
    {
        $categorias = Categoria::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categorias', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'conteudo' => 'required|string',
            'resumo' => 'nullable|string|max:500',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:rascunho,publicado',
            'destaque' => 'boolean',
            'tags' => 'array|exists:tags,id'
        ]);

        // Atualizar dados
        $data = [
            'titulo' => $request->titulo,
            'resumo' => $request->resumo,
            'conteudo' => $request->conteudo,
            'categoria_id' => $request->categoria_id,
            'status' => $request->status,
            'destaque' => $request->has('destaque')
        ];

        // Atualizar slug se título mudou
        if ($post->titulo != $request->titulo) {
            $slug = Str::slug($request->titulo);
            $count = Post::where('slug', $slug)->where('id', '!=', $post->id)->count();
            $data['slug'] = $count > 0 ? $slug . '-' . ($count + 1) : $slug;
        }

        // Atualizar data de publicação
        if ($request->status == 'publicado' && !$post->publicado_em) {
            $data['publicado_em'] = now();
        } elseif ($request->status == 'rascunho') {
            $data['publicado_em'] = null;
        }

        // Processar nova imagem
        if ($request->hasFile('imagem')) {
            // Deletar imagem antiga
            if ($post->imagem && Storage::disk('public')->exists($post->imagem)) {
                Storage::disk('public')->delete($post->imagem);
            }
            
            $imagem = $request->file('imagem');
            $data['imagem'] = $imagem->store('posts', 'public');
            $data['imagem_thumbnail'] = $data['imagem'];
        }

        $wasPublished = $post->status === 'publicado';

        $post->update($data);
        
        // Atualizar tags
        $post->tags()->sync($request->tags ?? []);

        if (! $wasPublished && $post->status === 'publicado') {
            app(WebPushService::class)->sendToAll([
                'title' => 'Novo post no Portal DevTech',
                'body' => Str::limit($post->resumo ?: $post->titulo, 140),
                'url' => route('post', $post->slug),
                'icon' => asset('icons/icon-192.png'),
                'badge' => asset('icons/badge-96.png'),
            ]);
        }

        return redirect()->route('admin.posts.index')
                         ->with('success', 'Post atualizado com sucesso!');
    }

    public function destroy(Post $post)
    {
        // Deletar imagem
        if ($post->imagem && Storage::disk('public')->exists($post->imagem)) {
            Storage::disk('public')->delete($post->imagem);
        }
        
        $post->delete();
        
        return redirect()->route('admin.posts.index')
                         ->with('success', 'Post deletado com sucesso!');
    }


    //comentários frontend

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

        // Verificar limite de comentários por IP (anti-spam)
        $lastComment = Comment::where('ip_address', $request->ip())
                              ->where('created_at', '>=', now()->subMinutes(5))
                              ->count();

        if ($lastComment >= 3) {
            return back()
                ->withInput()
                ->with('error', 'Você atingiu o limite de comentários. Aguarde alguns minutos para comentar novamente.');
        }

        // Salvar comentário
        try {
            $comment = Comment::create([
                'post_id' => $postId,
                'nome' => strip_tags($request->nome),
                'email' => $request->email,
                'comentario' => strip_tags($request->comentario),
                'aprovado' => false, // Precisa de aprovação
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Disparar evento para notificar admin (opcional)
            // event(new NewCommentSubmitted($comment));

            // Registrar log
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
