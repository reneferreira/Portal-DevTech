<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Categoria;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\Contato;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPosts = Post::count();
        $totalPublicados = Post::where('status', 'publicado')->count();
        $totalCategorias = Categoria::count();
        $totalTags = Tag::count();
        $totalComentarios = Comment::count();
        $comentariosPendentes = Comment::where('aprovado', false)->count();
        $totalContatos = Contato::count();
        $contatosNaoLidos = Contato::where('status', 'novo')->count();
        
        $postsRecentes = Post::with('categoria')
                            ->latest()
                            ->take(5)
                            ->get();
        
        $postsMaisVistos = Post::orderBy('views', 'desc')
                              ->take(5)
                              ->get();
        
        $postsPorMes = Post::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
                          ->whereYear('created_at', date('Y'))
                          ->groupBy('mes')
                          ->orderBy('mes')
                          ->get();
        
        return view('admin.dashboard', compact(
            'totalPosts', 'totalPublicados', 'totalCategorias', 'totalTags',
            'totalComentarios', 'comentariosPendentes', 'totalContatos', 
            'contatosNaoLidos', 'postsRecentes', 'postsMaisVistos', 'postsPorMes'
        ));
    }
}