{{-- resources/views/admin/categorias/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Nova Categoria')
@section('header', 'Criar Nova Categoria')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.categorias.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nome *</label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" 
                               value="{{ old('nome') }}" required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ícone (Bootstrap Icons)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-{{ old('icone', 'tag') }}"></i></span>
                            <input type="text" name="icone" class="form-control @error('icone') is-invalid @enderror" 
                                   value="{{ old('icone', 'tag') }}" placeholder="Ex: code-slash, phone, robot">
                        </div>
                        <small class="text-muted">
                            <a href="https://icons.getbootstrap.com/" target="_blank">Ver lista de ícones Bootstrap</a>
                        </small>
                        @error('icone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control @error('descricao') is-invalid @enderror" 
                                  rows="5">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Categoria</button>
            </div>
        </form>
    </div>
</div>
@endsection