{{-- resources/views/admin/videos/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Novo Vídeo')
@section('header', 'Adicionar Vídeo')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.videos.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" 
                               value="{{ old('titulo') }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ID do YouTube *</label>
                        <input type="text" name="youtube_id" class="form-control @error('youtube_id') is-invalid @enderror" 
                               value="{{ old('youtube_id') }}" placeholder="Ex: dQw4w9WgXcQ ou URL completa" required>
                        <small class="text-muted">Digite o ID do vídeo ou a URL completa do YouTube</small>
                        @error('youtube_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control @error('descricao') is-invalid @enderror" 
                                  rows="4">{{ old('descricao') }}</textarea>
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
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="rascunho" {{ old('status') == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                            <option value="publicado" {{ old('status') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                        </select>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="destaque" class="form-check-input" id="destaque" value="1" {{ old('destaque') ? 'checked' : '' }}>
                        <label class="form-check-label" for="destaque">
                            Marcar como destaque
                        </label>
                    </div>
                    
                    <!-- Preview do Vídeo -->
                    <div class="mt-3" id="videoPreview" style="display: none;">
                        <label class="form-label">Preview:</label>
                        <div class="ratio ratio-16x9">
                            <iframe id="youtubePreview" src="" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Vídeo</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview do YouTube
    const youtubeIdInput = document.querySelector('input[name="youtube_id"]');
    const previewDiv = document.getElementById('videoPreview');
    const youtubePreview = document.getElementById('youtubePreview');
    
    function extractYoutubeId(url) {
        const patterns = [
            /(?:youtube\.com\/watch\?v=)([^&\?]+)/,
            /(?:youtu\.be\/)([^&\?]+)/,
            /^([a-zA-Z0-9_-]{11})$/
        ];
        
        for (let pattern of patterns) {
            const match = url.match(pattern);
            if (match) return match[1];
        }
        return null;
    }
    
    youtubeIdInput.addEventListener('blur', function() {
        const value = this.value.trim();
        const videoId = extractYoutubeId(value);
        
        if (videoId) {
            youtubePreview.src = `https://www.youtube.com/embed/${videoId}`;
            previewDiv.style.display = 'block';
        } else if (value) {
            alert('ID do YouTube inválido!');
        }
    });
</script>
@endpush
@endsection