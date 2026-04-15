{{-- resources/views/admin/banners/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gerenciar Banners')
@section('header', 'Gerenciar Banners')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-2 mb-md-0">Lista de Banners</h5>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Novo Banner
                </a>
            </div>
            <div class="card-body p-0 p-md-3">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-nowrap">ID</th>
                                <th class="text-nowrap">Imagem</th>
                                <th class="text-nowrap">Título</th>
                                <th class="text-nowrap">Posição</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-nowrap">Ordem</th>
                                <th class="text-nowrap">Cliques</th>
                                <th class="text-nowrap">Expiração</th>
                                <th class="text-nowrap">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($banners as $banner)
                            <tr>
                                <td class="text-nowrap">#{{ $banner->id }}</td>
                                <td class="text-nowrap">
                                    @if($banner->imagem_url)
                                        <img src="{{ $banner->imagem_url }}" 
                                             style="width: 50px; height: 35px; object-fit: cover; border-radius: 5px;" 
                                             alt="{{ $banner->titulo }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 35px; border-radius: 5px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ Str::limit($banner->titulo, 35) }}</strong>
                                    @if($banner->link)
                                        <br><small class="text-muted text-wrap">{{ Str::limit($banner->link, 30) }}</small>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    @php
                                        $posicoes = [
                                            'topo' => '<i class="bi bi-arrow-up"></i> Topo',
                                            'sidebar' => '<i class="bi bi-layout-sidebar"></i> Sidebar',
                                            'entre_posts' => '<i class="bi bi-grid"></i> Entre Posts',
                                            'footer' => '<i class="bi bi-arrow-down"></i> Footer'
                                        ];
                                    @endphp
                                    {!! $posicoes[$banner->posicao] ?? $banner->posicao !!}
                                </td>
                                <td class="text-nowrap">
                                    @if($banner->ativo && $banner->isActive())
                                        <span class="badge bg-success">Ativo</span>
                                    @elseif(!$banner->ativo)
                                        <span class="badge bg-danger">Inativo</span>
                                    @else
                                        <span class="badge bg-warning">Expirado</span>
                                    @endif
                                </td>
                                <td class="text-nowrap" style="width: 80px;">
                                    <input type="number" class="form-control form-control-sm ordem-input" 
                                           data-id="{{ $banner->id }}" value="{{ $banner->ordem }}" 
                                           style="width: 60px;" min="0">
                                </td>
                                <td class="text-nowrap">{{ number_format($banner->clicks) }}</td>
                                <td class="text-nowrap">
                                    @if($banner->data_fim)
                                        {{ \Carbon\Carbon::parse($banner->data_fim)->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Sem data</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $banner->id }}"
                                                title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="bi bi-info-circle fs-4 text-muted"></i>
                                    <p class="text-muted mb-0">Nenhum banner encontrado.</p>
                                    <a href="{{ route('admin.banners.create') }}" class="btn btn-sm btn-primary mt-2">
                                        Criar primeiro banner
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($banners->count() > 0)
                    <div class="d-flex justify-content-center mt-4">
                        {{ $banners->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Atualizar ordem via AJAX
    $('.ordem-input').on('change', function() {
        const id = $(this).data('id');
        const ordem = $(this).val();
        
        $.ajax({
            url: '/admin/banners/' + id + '/ordem',
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                ordem: ordem
            },
            success: function(response) {
                if (response.success) {
                    // Feedback visual
                    const input = $('.ordem-input[data-id="' + id + '"]');
                    input.css('border-color', 'green');
                    setTimeout(() => input.css('border-color', ''), 2000);
                }
            },
            error: function() {
                alert('Erro ao atualizar ordem. Recarregue a página.');
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Garantir que a tabela não corte o conteúdo */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    /* Modal de confirmação */
    @foreach($banners as $banner)
    .modal.fade {
        z-index: 1050;
    }
    @endforeach
</style>
@endpush

<!-- Modais fora da tabela para não quebrar o layout -->
@foreach($banners as $banner)
<div class="modal fade" id="deleteModal{{ $banner->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir o banner "{{ $banner->titulo }}"?
                <br>
                <small class="text-danger">Esta ação não pode ser desfeita!</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection