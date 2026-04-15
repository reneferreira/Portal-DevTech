{{-- resources/views/admin/videos/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gerenciar Vídeos')
@section('header', 'Gerenciar Vídeos')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de Vídeos</h5>
        <a href="{{ route('admin.videos.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Novo Vídeo
        </a>
    </div>
    <div class="card-body">
        @if($videos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Thumb</th>
                            <th>Título</th>
                            <th>Status</th>
                            <th>Destaque</th>
                            <th>Views</th>
                            <th>Data</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($videos as $video)
                        <tr>
                            <td>#{{ $video->id }}</td>
                            <td>
                                <img src="{{ $video->thumbnail_url }}" style="width: 80px; height: 45px; object-fit: cover; border-radius: 5px;" alt="">
                            </td>
                            <td><strong>{{ Str::limit($video->titulo, 40) }}</strong></td>
                            <td>
                                @if($video->status == 'publicado')
                                    <span class="badge bg-success">Publicado</span>
                                @else
                                    <span class="badge bg-warning">Rascunho</span>
                                @endif
                            </td>
                            <td>
                                @if($video->destaque)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-muted"></i>
                                @endif
                            </td>
                            <td>{{ number_format($video->views) }}</td>
                            <td>{{ $video->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Excluir" onclick="return confirm('Excluir este vídeo?')">
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
                {{ $videos->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> Nenhum vídeo encontrado.
            </div>
        @endif
    </div>
</div>
@endsection