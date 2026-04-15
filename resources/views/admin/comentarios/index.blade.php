{{-- resources/views/admin/comentarios/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gerenciar Comentários')
@section('header', 'Gerenciar Comentários')

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
                        <i class="bi bi-chat-dots fs-2 text-primary"></i>
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
                        <h6 class="text-muted mb-1">Aprovados</h6>
                        <h3 class="mb-0 text-success">{{ number_format($stats['aprovados']) }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-check-circle fs-2 text-success"></i>
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
                        <h6 class="text-muted mb-1">Pendentes</h6>
                        <h3 class="mb-0 text-warning">{{ number_format($stats['pendentes']) }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="bi bi-hourglass-split fs-2 text-warning"></i>
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
                        <h6 class="text-muted mb-1">Hoje</h6>
                        <h3 class="mb-0 text-info">{{ number_format($stats['hoje']) }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="bi bi-calendar-day fs-2 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0">Lista de Comentários</h5>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width: 130px;" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendentes</option>
                    <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovados</option>
                </select>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Buscar..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        @if($comments->count() > 0)
            <!-- FORMULÁRIO DE AÇÃO EM MASSA - FECHAR ANTES DA TABELA -->
            <form action="{{ route('admin.comentarios.bulk') }}" method="POST" id="bulkForm" class="mb-3">
                @csrf
                <div class="d-flex gap-2 align-items-center">
                    <select name="action" class="form-select form-select-sm w-auto" required>
                        <option value="">Ação em massa</option>
                        <option value="aprovar">Aprovar selecionados</option>
                        <option value="excluir">Excluir selecionados</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Confirmar ação em massa?')">
                        Aplicar
                    </button>
                </div>
            </form>
            
            <!-- FIM DO FORMULÁRIO DE AÇÃO EM MASSA -->
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="30"><input type="checkbox" id="selectAll"></th>
                            <th width="50">ID</th>
                            <th>Autor</th>
                            <th>Comentário</th>
                            <th>Post</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comments as $comment)
                        <tr>
                            <td><input type="checkbox" name="comments[]" value="{{ $comment->id }}" class="comment-checkbox" form="bulkForm"></td>
                            <td>#{{ $comment->id }}</td>
                            <td>
                                <strong>{{ $comment->nome }}</strong><br>
                                <small class="text-muted">{{ $comment->email }}</small>
                                @if($comment->ip_address)
                                    <br><small class="text-muted">IP: {{ $comment->ip_address }}</small>
                                @endif
                             </td>
                            <td>
                                {{ Str::limit($comment->comentario, 80) }}
                             </td>
                            <td>
                                <a href="{{ route('post', $comment->post->slug) }}" target="_blank">
                                    {{ Str::limit($comment->post->titulo, 40) }}
                                </a>
                             </td>
                            <td>
                                @if($comment->aprovado)
                                    <span class="badge bg-success">Aprovado</span>
                                @else
                                    <span class="badge bg-warning">Pendente</span>
                                @endif
                             </td>
                            <td>
                                <small>
                                    {{ $comment->created_at->format('d/m/Y H:i') }}
                                </small>
                             </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <!-- Botão Visualizar -->
                                    <a href="{{ route('admin.comentarios.show', $comment) }}" class="btn btn-info" title="Visualizar">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <!-- Botão Aprovar - FORA do formulário bulk -->
                                    @if(!$comment->aprovado)
                                        <form action="{{ route('admin.comentarios.aprovar', $comment) }}" method="POST" class="d-inline" style="display: inline-block;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success" title="Aprovar" onclick="return confirm('Aprovar este comentário?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <!-- Botão Excluir - FORA do formulário bulk -->
                                    <form action="{{ route('admin.comentarios.destroy', $comment) }}" method="POST" class="d-inline" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este comentário?')">
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
                {{ $comments->appends(request()->query())->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> Nenhum comentário encontrado.
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Select all checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.comment-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
    
    // Confirmar ação em massa
    document.getElementById('bulkForm')?.addEventListener('submit', function(e) {
        const action = document.querySelector('select[name="action"]').value;
        const checkboxes = document.querySelectorAll('.comment-checkbox:checked');
        
        if (!action) {
            e.preventDefault();
            alert('Selecione uma ação primeiro.');
            return false;
        }
        
        if (checkboxes.length === 0) {
            e.preventDefault();
            alert('Selecione pelo menos um comentário.');
            return false;
        }
        
        return confirm(`Confirmar ${action === 'aprovar' ? 'aprovação' : 'exclusão'} de ${checkboxes.length} comentário(s)?`);
    });
</script>
@endpush