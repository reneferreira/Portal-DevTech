<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('categoria', 'user')
                       ->latest()
                       ->paginate(15);
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.videos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'youtube_id' => 'required|string|max:20',
            'descricao' => 'nullable|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'destaque' => 'boolean',
            'status' => 'required|in:rascunho,publicado'
        ]);

        // Extrair ID do YouTube se for URL completa
        $youtubeId = $request->youtube_id;
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?]+)/', $youtubeId, $matches)) {
            $youtubeId = $matches[1];
        }

        $video = Video::create([
            'titulo' => $request->titulo,
            'youtube_id' => $youtubeId,
            'descricao' => $request->descricao,
            'categoria_id' => $request->categoria_id,
            'user_id' => auth()->id(),
            'destaque' => $request->has('destaque'),
            'status' => $request->status,
            'publicado_em' => $request->status == 'publicado' ? now() : null
        ]);

        Log::info('Vídeo criado', ['video_id' => $video->id, 'user_id' => auth()->id()]);

        return redirect()->route('admin.videos.index')
                         ->with('success', 'Vídeo criado com sucesso!');
    }

    public function edit(Video $video)
    {
        $categorias = Categoria::all();
        return view('admin.videos.edit', compact('video', 'categorias'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'youtube_id' => 'required|string|max:20',
            'descricao' => 'nullable|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'destaque' => 'boolean',
            'status' => 'required|in:rascunho,publicado'
        ]);

        // Extrair ID do YouTube
        $youtubeId = $request->youtube_id;
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?]+)/', $youtubeId, $matches)) {
            $youtubeId = $matches[1];
        }

        $data = [
            'titulo' => $request->titulo,
            'youtube_id' => $youtubeId,
            'descricao' => $request->descricao,
            'categoria_id' => $request->categoria_id,
            'destaque' => $request->has('destaque'),
            'status' => $request->status,
        ];

        if ($request->status == 'publicado' && !$video->publicado_em) {
            $data['publicado_em'] = now();
        }

        $video->update($data);

        Log::info('Vídeo atualizado', ['video_id' => $video->id, 'user_id' => auth()->id()]);

        return redirect()->route('admin.videos.index')
                         ->with('success', 'Vídeo atualizado com sucesso!');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        
        Log::info('Vídeo excluído', ['video_id' => $video->id, 'user_id' => auth()->id()]);
        
        return redirect()->route('admin.videos.index')
                         ->with('success', 'Vídeo excluído com sucesso!');
    }
}