{{-- resources/views/admin/contatos/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detalhes do Contato')
@section('header', 'Detalhes do Contato')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Mensagem de Contato #{{ $contato->id }}</h5>
            <div>
                @if($contato->status != 'respondido')
                    <form action="{{ route('admin.contatos.respondido', $contato) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Marcar como respondido?')">
                            <i class="bi bi-check-circle"></i> Marcar como Respondido
                        </button>
                    </form>
                @endif
                <form action="{{ route('admin.contatos.destroy', $contato) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Excluir este contato?')">
                        <i class="bi bi-trash"></i> Excluir
                    </button>
                </form>
                <a href="{{ route('admin.contatos.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Nome:</th>
                        <td>{{ $contato->nome }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><a href="mailto:{{ $contato->email }}">{{ $contato->email }}</a></td>
                    </tr>
                    <tr>
                        <th>Telefone:</th>
                        <td>{{ $contato->telefone ?? 'Não informado' }}</td>
                    </tr>
                    <tr>
                        <th>Assunto:</th>
                        <td><span class="badge bg-info">{{ $contato->assunto }}</span></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($contato->status == 'novo')
                                <span class="badge bg-warning">Novo</span>
                            @elseif($contato->status == 'lido')
                                <span class="badge bg-success">Lido</span>
                            @else
                                <span class="badge bg-secondary">Respondido</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Data:</th>
                        <td>{{ $contato->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12">
                <h6>Mensagem:</h6>
                <div class="bg-light p-3 rounded">
                    {{ nl2br(e($contato->mensagem)) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection