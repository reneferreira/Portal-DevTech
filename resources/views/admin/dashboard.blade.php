{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<!-- Cards de Estatísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stats border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total de Posts</h6>
                        <h2 class="mb-0">{{ number_format($totalPosts) }}</h2>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> {{ $totalPublicados }} publicados
                        </small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-newspaper fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stats border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Categorias</h6>
                        <h2 class="mb-0">{{ number_format($totalCategorias) }}</h2>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-grid fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stats border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Comentários</h6>
                        <h2 class="mb-0">{{ number_format($totalComentarios) }}</h2>
                        @if($comentariosPendentes > 0)
                            <small class="text-warning">
                                <i class="bi bi-clock"></i> {{ $comentariosPendentes }} pendentes
                            </small>
                        @endif
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="bi bi-chat-dots fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stats border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Contatos</h6>
                        <h2 class="mb-0">{{ number_format($totalContatos) }}</h2>
                        @if($contatosNaoLidos > 0)
                            <small class="text-danger">
                                <i class="bi bi-envelope"></i> {{ $contatosNaoLidos }} não lidos
                            </small>
                        @endif
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="bi bi-envelope fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Posts Recentes -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Posts Recentes</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($postsRecentes as $post)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-decoration-none">
                                        <strong>{{ Str::limit($post->titulo, 50) }}</strong>
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-tag"></i> {{ $post->categoria->nome }}
                                    </small>
                                </div>
                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Posts Mais Vistos -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Posts Mais Vistos</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($postsMaisVistos as $post)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-decoration-none">
                                        {{ Str::limit($post->titulo, 50) }}
                                    </a>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    <i class="bi bi-eye"></i> {{ number_format($post->views) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráfico de Posts por Mês -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Posts por Mês - {{ date('Y') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="postsChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Novo Post
                    </a>
                    <a href="{{ route('admin.categorias.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-lg"></i> Nova Categoria
                    </a>
                    <a href="{{ route('admin.tags.create') }}" class="btn btn-info">
                        <i class="bi bi-plus-lg"></i> Nova Tag
                    </a>
                    @if($comentariosPendentes > 0)
                        <a href="{{ route('admin.comentarios.index') }}" class="btn btn-warning">
                            <i class="bi bi-chat-dots"></i> Aprovar Comentários ({{ $comentariosPendentes }})
                        </a>
                    @endif
                    @if($contatosNaoLidos > 0)
                        <a href="{{ route('admin.contatos.index') }}" class="btn btn-danger">
                            <i class="bi bi-envelope"></i> Ver Contatos ({{ $contatosNaoLidos }})
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('postsChart').getContext('2d');
    const postsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            datasets: [{
                label: 'Posts criados',
                data: [
                    {{ $postsPorMes->firstWhere('mes', 1)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 2)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 3)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 4)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 5)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 6)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 7)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 8)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 9)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 10)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 11)->total ?? 0 }},
                    {{ $postsPorMes->firstWhere('mes', 12)->total ?? 0 }}
                ],
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
@endpush