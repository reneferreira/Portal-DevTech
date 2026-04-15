{{-- resources/views/admin/posts/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Novo Post')
@section('header', 'Criar Novo Post')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label class="form-label">Resumo</label>
                        <textarea name="resumo" class="form-control @error('resumo') is-invalid @enderror" 
                                  rows="3">{{ old('resumo') }}</textarea>
                        <small class="text-muted">Breve descrição do post (máx. 500 caracteres)</small>
                        @error('resumo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Conteúdo *</label>
                        <textarea name="conteudo" id="conteudo" class="form-control @error('conteudo') is-invalid @enderror" 
                                  required>{{ old('conteudo') }}</textarea>
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
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
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
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                    {{ $tag->nome }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Selecione uma ou mais tags</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Imagem de Destaque</label>
                        <input type="file" name="imagem" class="form-control @error('imagem') is-invalid @enderror" 
                               accept="image/*">
                        <small class="text-muted">Formatos: JPG, PNG, GIF (máx. 2MB)</small>
                        @error('imagem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="rascunho" {{ old('status') == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                            <option value="publicado" {{ old('status') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                        </select>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="destaque" class="form-check-input" id="destaque" 
                               value="1" {{ old('destaque') ? 'checked' : '' }}>
                        <label class="form-check-label" for="destaque">
                            Marcar como destaque
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Post</button>
            </div>
        </form>
    </div>
</div>
@endsection