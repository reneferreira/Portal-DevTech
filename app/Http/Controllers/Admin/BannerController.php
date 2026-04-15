<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('ordem')->orderBy('id', 'desc')->paginate(15);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagem' => 'required_if:tipo,imagem|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'posicao' => 'required|in:topo,sidebar,entre_posts,footer',
            'tipo' => 'required|in:imagem,video,html',
            'html_code' => 'required_if:tipo,html',
            'ativo' => 'boolean',
            'ordem' => 'integer',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after:data_inicio'
        ]);

        $data = [
            'titulo' => $request->titulo,
            'slug' => Str::slug($request->titulo) . '-' . uniqid(),
            'descricao' => $request->descricao,
            'link' => $request->link,
            'posicao' => $request->posicao,
            'tipo' => $request->tipo,
            'html_code' => $request->html_code,
            'ativo' => $request->has('ativo'),
            'ordem' => $request->ordem ?? 0,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim
        ];

        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')
                         ->with('success', 'Banner criado com sucesso!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'posicao' => 'required|in:topo,sidebar,entre_posts,footer',
            'tipo' => 'required|in:imagem,video,html',
            'html_code' => 'required_if:tipo,html',
            'ativo' => 'boolean',
            'ordem' => 'integer',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after:data_inicio'
        ]);

        $data = [
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'link' => $request->link,
            'posicao' => $request->posicao,
            'tipo' => $request->tipo,
            'html_code' => $request->html_code,
            'ativo' => $request->has('ativo'),
            'ordem' => $request->ordem ?? 0,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim
        ];

        if ($request->hasFile('imagem')) {
            if ($banner->imagem && Storage::disk('public')->exists($banner->imagem)) {
                Storage::disk('public')->delete($banner->imagem);
            }
            $data['imagem'] = $request->file('imagem')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
                         ->with('success', 'Banner atualizado com sucesso!');
    }
    

    public function updateOrdem(Request $request, Banner $banner)
    {
        $request->validate([
            'ordem' => 'required|integer|min:0'
        ]);
        
        $banner->update(['ordem' => $request->ordem]);
        
        return response()->json(['success' => true]);
    }

    public function destroy(Banner $banner)
    {
        if ($banner->imagem && Storage::disk('public')->exists($banner->imagem)) {
            Storage::disk('public')->delete($banner->imagem);
        }
        
        $banner->delete();

        return redirect()->route('admin.banners.index')
                         ->with('success', 'Banner excluído com sucesso!');
    }
}