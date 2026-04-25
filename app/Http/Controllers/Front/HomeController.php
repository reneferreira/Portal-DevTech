<?php
// app/Http/Controllers/Front/HomeController.php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Categoria;
use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Categorias com contagem de posts
        $categorias = Categoria::withCount('posts')->get();
        
        // Posts em destaque (carrossel)
        $destaques = Post::with(['categoria', 'user'])
                        ->publicado()
                        ->destaque()
                        ->latest('publicado_em')
                        ->take(6)
                        ->get();
        
        // Últimos posts (página inicial)
        $posts = Post::with(['categoria', 'user'])
                    ->publicado()
                    ->latest('publicado_em')
                    ->paginate(10);
        
        // Mais lidas da semana
        $maisLidas = Post::publicado()
                        ->where('publicado_em', '>=', now()->subDays(7))
                        ->orderBy('views', 'desc')
                        ->take(5)
                        ->get();
        
        // VÍDEOS - Correção principal
        $videos = Video::publicado() // Usando o scope publicado
                      ->latest('publicado_em')
                      ->take(8)
                      ->get();
        
        // Se não houver vídeos no banco, usar exemplos
        if ($videos->isEmpty()) {
            $videos = collect([
                (object) [
                    'titulo' => 'Como começar em Laravel',
                    'youtube_id' => 'dQw4w9WgXcQ',
                    'thumbnail' => null,
                    'views' => 1200,
                    'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
                ],
                (object) [
                    'titulo' => 'React vs Vue 2024',
                    'youtube_id' => 'dQw4w9WgXcQ',
                    'thumbnail' => null,
                    'views' => 890,
                    'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
                ],
                (object) [
                    'titulo' => 'Docker para iniciantes',
                    'youtube_id' => 'dQw4w9WgXcQ',
                    'thumbnail' => null,
                    'views' => 2100,
                    'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
                ],
                (object) [
                    'titulo' => 'APIs RESTful com Laravel',
                    'youtube_id' => 'dQw4w9WgXcQ',
                    'thumbnail' => null,
                    'views' => 3450,
                    'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
                ],
                (object) [
                    'titulo' => 'JavaScript Moderno',
                    'youtube_id' => 'dQw4w9WgXcQ',
                    'thumbnail' => null,
                    'views' => 980,
                    'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
                ],
                (object) [
                    'titulo' => 'Banco de Dados SQL',
                    'youtube_id' => 'dQw4w9WgXcQ',
                    'thumbnail' => null,
                    'views' => 1560,
                    'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
                ],
                (object) [
                    'titulo' => 'Git e GitHub',
                    'youtube_id' => 'dQw4w9WgXcQ',
                    'thumbnail' => null,
                    'views' => 2870,
                    'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
                ],
                (object) [
                    'titulo' => 'Tailwind CSS',
                    'youtube_id' => 'dQw4w9WgXcQ',
                    'thumbnail' => null,
                    'views' => 430,
                    'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
                ],
            ]);
        }
        
        // ARTIGOS - Correção
        // Verificar se existe o campo 'tipo' na tabela posts
        $hasTipoColumn = \Illuminate\Support\Facades\Schema::hasColumn('posts', 'tipo');
        
        if ($hasTipoColumn) {
            $artigos = Post::with(['categoria', 'user'])
                          ->publicado()
                          ->where('tipo', 'artigo')
                          ->latest('publicado_em')
                          ->take(8)
                          ->get();
        } else {
            // Fallback: usar posts de uma categoria específica chamada "Artigos"
            $artigos = Post::with(['categoria', 'user'])
                          ->publicado()
                          ->whereHas('categoria', function($query) {
                              $query->where('nome', 'like', '%Artigo%')
                                    ->orWhere('nome', 'like', '%Blog%');
                          })
                          ->latest('publicado_em')
                          ->take(8)
                          ->get();
        }
        
        // Se ainda não tiver artigos, usar os últimos posts
        if ($artigos->isEmpty()) {
            $artigos = Post::with(['categoria', 'user'])
                          ->publicado()
                          ->latest('publicado_em')
                          ->take(8)
                          ->get();
        }
        
        return view('front.home', compact(
            'categorias', 
            'destaques', 
            'posts', 
            'maisLidas', 
            'videos', 
            'artigos'
        ));
    }

    public function busca(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        if ($query === '') {
            return redirect()
                ->route('home')
                ->with('warning', 'Digite um termo para buscar notícias.');
        }

        $searchTerms = collect(preg_split('/\s+/', $query))
            ->filter()
            ->unique()
            ->values();
        
        $posts = Post::with(['categoria', 'user', 'tags'])
                    ->publicado()
                    ->where(function($q) use ($query, $searchTerms) {
                        $q->where('titulo', 'LIKE', "%{$query}%")
                          ->orWhere('conteudo', 'LIKE', "%{$query}%")
                          ->orWhere('resumo', 'LIKE', "%{$query}%")
                          ->orWhereHas('categoria', function ($categoriaQuery) use ($query) {
                              $categoriaQuery->where('nome', 'LIKE', "%{$query}%")
                                  ->orWhere('descricao', 'LIKE', "%{$query}%");
                          })
                          ->orWhereHas('tags', function ($tagQuery) use ($query) {
                              $tagQuery->where('nome', 'LIKE', "%{$query}%");
                          });

                        $searchTerms->each(function ($term) use ($q) {
                            $q->orWhere(function ($termQuery) use ($term) {
                                $termQuery->where('titulo', 'LIKE', "%{$term}%")
                                    ->orWhere('conteudo', 'LIKE', "%{$term}%")
                                    ->orWhere('resumo', 'LIKE', "%{$term}%")
                                    ->orWhereHas('categoria', function ($categoriaQuery) use ($term) {
                                        $categoriaQuery->where('nome', 'LIKE', "%{$term}%")
                                            ->orWhere('descricao', 'LIKE', "%{$term}%");
                                    })
                                    ->orWhereHas('tags', function ($tagQuery) use ($term) {
                                        $tagQuery->where('nome', 'LIKE', "%{$term}%");
                                    });
                            });
                        });
                    })
                    ->latest('publicado_em')
                    ->paginate(10)
                    ->withQueryString();

        return view('front.busca', compact('posts', 'query', 'searchTerms'));
    }
}
