<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $categoria = Categoria::where('slug', $slug)->firstOrFail();

        $posts = Post::with(['categoria', 'user'])
                    ->where('categoria_id', $categoria->id)
                    ->where('status', 'publicado')
                    ->where('publicado_em', '<=', now())
                    ->latest('publicado_em')
                    ->paginate(9);

        return view('front.categoria', compact('categoria', 'posts'));
    }
}