{{-- resources/views/admin/videos/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Editar Vídeo')
@section('header', 'Editar Vídeo')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.videos.update', $video) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" 
                               value="{{ old('titulo', $video->titulo) }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ID do YouTube *</label>
                        <input type="text" name="youtube_id" class="form-control @error('youtube_id') is-invalid @enderror" 
                               value="{{ old('youtube_id', $video->youtube_id) }}" required>
                        <small class="text-muted">Digite o ID do vídeo ou a URL completa do YouTube</small>
                        @error('youtube_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control @error('descricao') is-invalid @enderror" 
                                  rows="4">{{ old('descricao', $video->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Categoria</label>
                        <select name="categoria_id" class="form-select">
                            <option value="">Sem categoria</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $video->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="rascunho" {{ old('status', $video->status) == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                            <option value="publicado" {{ old('status', $video->status) == 'publicado' ? 'selected' : '' }}>Publicado</option>
                        </select>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="destaque" class="form-check-input" id="destaque" value="1" {{ old('destaque', $video->destaque) ? 'checked' : '' }}>
                        <label class="form-check-label" for="destaque">
                            Marcar como destaque
                        </label>
                    </div>
                    
                    <!-- Preview do Vídeo -->
                    <div class="mt-3">
                        <label class="form-label">Preview:</label>
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $video->embed_url }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Atualizar Vídeo</button>
            </div>
        </form>
    </div>
</div>
@endsection