{{-- resources/views/admin/comentarios/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detalhes do Comentário')
@section('header', 'Detalhes do Comentário')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informações do Comentário</h5>
                    <div>
                        @if(!$comment->aprovado)
                            <form action="{{ route('admin.comentarios.aprovar', $comment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Aprovar este comentário?')">
                                    <i class="bi bi-check-lg"></i> Aprovar
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.comentarios.destroy', $comment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Excluir este comentário?')">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </form>
                        <a href="{{ route('admin.comentarios.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">ID:</div>
                    <div class="col-md-9">#{{ $comment->id }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Autor:</div>
                    <div class="col-md-9">
                        <strong>{{ $comment->nome }}</strong><br>
                        <a href="mailto:{{ $comment->email }}">{{ $comment->email }}</a>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Status:</div>
                    <div class="col-md-9">
                        @if($comment->aprovado)
                            <span class="badge bg-success">Aprovado</span>
                        @else
                            <span class="badge bg-warning">Pendente</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Post:</div>
                    <div class="col-md-9">
                        <a href="{{ route('post', $comment->post->slug) }}" target="_blank">
                            {{ $comment->post->titulo }}
                        </a>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Data:</div>
                    <div class="col-md-9">
                        {{ $comment->created_at->format('d/m/Y \à\s H:i:s') }}
                        <small class="text-muted">({{ $comment->created_at->diffForHumans() }})</small>
                    </div>
                </div>
                
                @if($comment->ip_address)
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">IP Address:</div>
                    <div class="col-md-9">
                        <code>{{ $comment->ip_address }}</code>
                    </div>
                </div>
                @endif
                
                @if($comment->user_agent)
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Navegador:</div>
                    <div class="col-md-9">
                        <small class="text-muted">{{ $comment->user_agent }}</small>
                    </div>
                </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Comentário:</div>
                    <div class="col-md-9">
                        <div class="bg-light p-3 rounded">
                            {{ nl2br(e($comment->comentario)) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(!$comment->aprovado)
                        <form action="{{ route('admin.comentarios.aprovar', $comment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-lg"></i> Aprovar Comentário
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.comentarios.destroy', $comment) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Excluir este comentário permanentemente?')">
                            <i class="bi bi-trash"></i> Excluir Comentário
                        </button>
                    </form>
                    
                    <hr>
                    
                    <a href="{{ route('admin.comentarios.index') }}" class="btn btn-secondary">
                        <i class="bi bi-list"></i> Todos os Comentários
                    </a>
                    
                    <a href="{{ route('post', $comment->post->slug) }}" target="_blank" class="btn btn-info">
                        <i class="bi bi-eye"></i> Ver Post no Site
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informações Adicionais</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <i class="bi bi-calendar"></i> 
                    <strong>Criado:</strong> {{ $comment->created_at->format('d/m/Y H:i') }}
                </p>
                <p class="mb-2">
                    <i class="bi bi-pencil"></i> 
                    <strong>Última atualização:</strong> {{ $comment->updated_at->format('d/m/Y H:i') }}
                </p>
                @if($comment->post->user)
                <p class="mb-0">
                    <i class="bi bi-person"></i> 
                    <strong>Autor do Post:</strong> {{ $comment->post->user->name }}
                </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection