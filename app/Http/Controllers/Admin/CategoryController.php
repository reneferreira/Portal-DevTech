<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('posts')->latest()->paginate(10);
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:categorias',
            'descricao' => 'nullable|string',
            'icone' => 'nullable|string|max:50'
        ]);

        Categoria::create([
            'nome' => $request->nome,
            'slug' => Str::slug($request->nome),
            'descricao' => $request->descricao,
            'icone' => $request->icone ?? 'tag'
        ]);

        return redirect()->route('admin.categorias.index')
                         ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:categorias,nome,' . $categoria->id,
            'descricao' => 'nullable|string',
            'icone' => 'nullable|string|max:50'
        ]);

        $data = [
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'icone' => $request->icone ?? 'tag'
        ];

        if ($categoria->nome != $request->nome) {
            $data['slug'] = Str::slug($request->nome);
        }

        $categoria->update($data);

        return redirect()->route('admin.categorias.index')
                         ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->posts()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma categoria que possui posts!');
        }

        $categoria->delete();

        return redirect()->route('admin.categorias.index')
                         ->with('success', 'Categoria excluída com sucesso!');
    }
}