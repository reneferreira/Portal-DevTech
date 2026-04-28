@extends('layouts.admin')

@section('title', 'Notificacoes Push')
@section('header', 'Notificacoes Push')

@section('content')
<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-bell"></i> Enviar notificacao</h5>
            </div>
            <div class="card-body">
                @if(! $subscriptionsTableExists)
                    <div class="alert alert-danger">
                        A tabela <code>push_subscriptions</code> ainda nao existe. Execute <code>php artisan migrate</code> no ambiente de producao para ativar as notificacoes.
                    </div>
                @endif

                @if(! $publicKeyConfigured || ! $privateKeyConfigured)
                    <div class="alert alert-warning">
                        Configure <code>VAPID_PUBLIC_KEY</code> e <code>VAPID_PRIVATE_KEY</code> no ambiente antes de enviar notificacoes.
                        Gere as chaves com <code>php artisan push:vapid-keys</code>. Se ja configurou, limpe o cache com <code>php artisan config:clear</code>.
                    </div>
                @endif

                @if($subscriptionsTableExists && $subscriptionsCount === 0)
                    <div class="alert alert-info">
                        Nenhum dispositivo esta inscrito ainda. Clique em <strong>Ativar notificacoes</strong> neste navegador e permita as notificacoes para habilitar os envios.
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.push.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Titulo</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" maxlength="120" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">Mensagem</label>
                        <textarea id="body" name="body" class="form-control @error('body') is-invalid @enderror" rows="4" maxlength="240" required>{{ old('body') }}</textarea>
                        @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="url" class="form-label">URL de destino</label>
                        <input type="url" id="url" name="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', route('home')) }}">
                        @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary" @disabled(! $subscriptionsTableExists || ! $publicKeyConfigured || ! $privateKeyConfigured || $subscriptionsCount === 0)>
                        <i class="bi bi-send"></i> Enviar agora
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Dispositivos inscritos</h6>
                        <h2 class="mb-0">{{ number_format($subscriptionsCount) }}</h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-phone-vibrate fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Inscricoes recentes</h5>
            </div>
            <div class="card-body">
                @forelse($recentSubscriptions as $subscription)
                    <div class="border-bottom pb-3 mb-3">
                        <strong>{{ $subscription->user?->name ?? 'Visitante' }}</strong>
                        <div class="text-muted small">{{ Str::limit($subscription->user_agent ?: $subscription->endpoint, 90) }}</div>
                        <div class="text-muted small">{{ $subscription->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Nenhum dispositivo inscrito ainda.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
