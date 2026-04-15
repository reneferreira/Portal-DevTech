{{-- resources/views/admin/tags/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Nova Tag')
@section('header', 'Criar Nova Tag')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.tags.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Nome da Tag *</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" 
                       value="{{ old('nome') }}" placeholder="Ex: Laravel, PHP, JavaScript" required>
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">A slug será gerada automaticamente</small>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Tag</button>
            </div>
        </form>
    </div>
</div>
@endsection