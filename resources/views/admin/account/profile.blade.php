{{-- resources/views/admin/account/profile.blade.php --}}
@extends('layouts.admin')

@section('title', 'Meu Perfil')
@section('header', 'Meu Perfil')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person-circle text-primary"></i> Dados da conta</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            autofocus
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="form-text text-warning">
                                Este e-mail ainda precisa ser verificado.
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Salvar perfil
                        </button>
                        <a href="{{ route('admin.profile.password') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-shield-lock"></i> Mudar senha
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 1.4rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <small class="text-muted">{{ $user->is_admin ? 'Administrador' : 'Usuário' }}</small>
                    </div>
                </div>
                <dl class="row mb-0 small">
                    <dt class="col-5">Cadastro</dt>
                    <dd class="col-7">{{ $user->created_at?->format('d/m/Y H:i') }}</dd>
                    <dt class="col-5">Verificação</dt>
                    <dd class="col-7">
                        @if($user->email_verified_at)
                            <span class="badge bg-success">Verificado</span>
                        @else
                            <span class="badge bg-warning text-dark">Pendente</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
