{{-- resources/views/admin/posts/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Editar Post')
@section('header', 'Editar Post')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" 
                               value="{{ old('titulo', $post->titulo) }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Resumo</label>
                        <textarea name="resumo" class="form-control @error('resumo') is-invalid @enderror" 
                                  rows="3">{{ old('resumo', $post->resumo) }}</textarea>
                        @error('resumo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Conteúdo *</label>
                        <textarea name="conteudo" id="conteudo" class="form-control @error('conteudo') is-invalid @enderror" 
                                  required>{{ old('conteudo', $post->conteudo) }}</textarea>
                        @error('conteudo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Categoria *</label>
                        <select name="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror" required>
                            <option value="">Selecione...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" 
                                    {{ old('categoria_id', $post->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <select name="tags[]" class="select2" multiple>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" 
                                    {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $tag->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Imagem Atual</label>
                        @if($post->imagem)
                            <div class="mb-2">
                                <img src="{{ ImageHelper::getImageUrl($post->imagem) }}" 
                                     class="img-fluid rounded" alt="{{ $post->titulo }}">
                            </div>
                        @else
                            <p class="text-muted">Nenhuma imagem definida</p>
                        @endif
                        
                        <label class="form-label">Alterar Imagem</label>
                        <input type="file" name="imagem" class="form-control @error('imagem') is-invalid @enderror" 
                               accept="image/*">
                        @error('imagem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="rascunho" {{ old('status', $post->status) == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                            <option value="publicado" {{ old('status', $post->status) == 'publicado' ? 'selected' : '' }}>Publicado</option>
                        </select>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="destaque" class="form-check-input" id="destaque" 
                               value="1" {{ old('destaque', $post->destaque) ? 'checked' : '' }}>
                        <label class="form-check-label" for="destaque">
                            Marcar como destaque
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Atualizar Post</button>
            </div>
        </form>
    </div>
</div>
@endsection
