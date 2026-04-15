{{-- resources/views/admin/tags/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gerenciar Tags')
@section('header', 'Gerenciar Tags')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Tags</h5>
            <a href="{{ route('admin.tags.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Nova Tag
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Slug</th>
                        <th>Posts</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tags as $tag)
                    <tr>
                        <td>{{ $tag->id }}</td>
                        <td><span class="badge bg-secondary">{{ $tag->nome }}</span></td>
                        <td><code>{{ $tag->slug }}</code></td>
                        <td><span class="badge bg-info">{{ $tag->posts_count }}</span></td>
                        <td>{{ $tag->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $tag->id }}"
                                        title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <div class="modal fade" id="deleteModal{{ $tag->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmar exclusão</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Tem certeza que deseja excluir a tag "{{ $tag->nome }}"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST">
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
            {{ $tags->links() }}
        </div>
    </div>
</div>
@endsection