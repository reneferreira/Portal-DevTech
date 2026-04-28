{{-- resources/views/admin/posts/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gerenciar Posts')
@section('header', 'Gerenciar Posts')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Posts</h5>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Novo Post
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Destaque</th>
                        <th>Views</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>
                            <strong>{{ Str::limit($post->titulo, 50) }}</strong>
                              @if($post->imagem)
                                    <img src="{{ ImageHelper::getImageUrl($post->imagem) }}" 
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;" 
                                        alt="{{ $post->titulo }}">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                        style="width: 50px; height: 50px; border-radius: 5px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $post->categoria->nome }}</span>
                        </td>
                        <td>
                            @if($post->status == 'publicado')
                                <span class="badge bg-success">Publicado</span>
                            @else
                                <span class="badge bg-warning">Rascunho</span>
                            @endif
                        </td>
                        <td>
                            @if($post->destaque)
                                <i class="bi bi-star-fill text-warning"></i>
                            @else
                                <i class="bi bi-star text-muted"></i>
                            @endif
                        </td>
                        <td>{{ number_format($post->views) }}</td>
                        <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('post', $post->slug) }}" target="_blank" 
                                   class="btn btn-info" title="Ver no site">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.posts.edit', $post) }}" 
                                   class="btn btn-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $post->id }}"
                                        title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Modal de Confirmação -->
                            <div class="modal fade" id="deleteModal{{ $post->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmar exclusão</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Tem certeza que deseja excluir o post "{{ $post->titulo }}"?
                                            <br>
                                            <small class="text-danger">Esta ação não pode ser desfeita!</small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Excluir</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
