{{-- resources/views/admin/contatos/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gerenciar Contatos')
@section('header', 'Gerenciar Contatos')

@section('content')
<!-- Cards de Estatísticas -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total</h6>
                        <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-envelope fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Novos</h6>
                        <h3 class="mb-0 text-warning">{{ number_format($stats['novos']) }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="bi bi-envelope-plus fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Lidos</h6>
                        <h3 class="mb-0 text-info">{{ number_format($stats['lidos']) }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="bi bi-envelope-open fs-2 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Respondidos</h6>
                        <h3 class="mb-0 text-success">{{ number_format($stats['respondidos']) }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Lista de Contatos</h5>
    </div>
    <div class="card-body">
        @if($contatos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Assunto</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contatos as $contato)
                        <tr>
                            <td>#{{ $contato->id }}</td>
                            <td><strong>{{ $contato->nome }}</strong></td>
                            <td>{{ $contato->email }}</td>
                            <td>{{ $contato->assunto }}</td>
                            <td>
                                @if($contato->status == 'novo')
                                    <span class="badge bg-warning">Novo</span>
                                @elseif($contato->status == 'lido')
                                    <span class="badge bg-info">Lido</span>
                                @else
                                    <span class="badge bg-success">Respondido</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $contato->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.contatos.show', $contato) }}" class="btn btn-info" title="Visualizar">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.contatos.destroy', $contato) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Excluir" onclick="return confirm('Excluir este contato?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $contatos->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> Nenhum contato encontrado.
            </div>
        @endif
    </div>
</div>
@endsection