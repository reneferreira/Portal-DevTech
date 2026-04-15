{{-- resources/views/admin/categorias/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gerenciar Categorias')
@section('header', 'Gerenciar Categorias')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Categorias</h5>
            <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Nova Categoria
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ícone</th>
                        <th>Nome</th>
                        <th>Slug</th>
                        <th>Posts</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->id }}</td>
                        <td><i class="bi bi-{{ $categoria->icone }} fs-5"></i></td>
                        <td><strong>{{ $categoria->nome }}</strong></td>
                        <td><code>{{ $categoria->slug }}</code></td>
                        <td><span class="badge bg-info">{{ $categoria->posts_count }}</span></td>
                        <td>{{ $categoria->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.categorias.edit', $categoria) }}" 
                                   class="btn btn-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($categoria->posts_count == 0)
                                    <button type="button" class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $categoria->id }}"
                                            title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary" disabled title="Não pode excluir (possui posts)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                            
                            @if($categoria->posts_count == 0)
                            <div class="modal fade" id="deleteModal{{ $categoria->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmar exclusão</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Tem certeza que deseja excluir a categoria "{{ $categoria->nome }}"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('admin.categorias.destroy', $categoria) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Excluir</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                         </td>
                     </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $categorias->links() }}
        </div>
    </div>
</div>
@endsection