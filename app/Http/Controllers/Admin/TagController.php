<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')->latest()->paginate(10);
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:tags'
        ]);

        Tag::create([
            'nome' => $request->nome,
            'slug' => Str::slug($request->nome)
        ]);

        return redirect()->route('admin.tags.index')
                         ->with('success', 'Tag criada com sucesso!');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:tags,nome,' . $tag->id
        ]);

        $data = ['nome' => $request->nome];
        
        if ($tag->nome != $request->nome) {
            $data['slug'] = Str::slug($request->nome);
        }

        $tag->update($data);

        return redirect()->route('admin.tags.index')
                         ->with('success', 'Tag atualizada com sucesso!');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        
        return redirect()->route('admin.tags.index')
                         ->with('success', 'Tag excluída com sucesso!');
    }
}