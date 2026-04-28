{{-- resources/views/admin/account/password.blade.php --}}
@extends('layouts.admin')

@section('title', 'Mudar Senha')
@section('header', 'Mudar Senha')

@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-shield-lock text-primary"></i> Segurança da conta</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.password.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Senha atual</label>
                        <input
                            type="password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            id="current_password"
                            name="current_password"
                            required
                            autocomplete="current-password"
                        >
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nova senha</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                        >
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Alterar senha
                        </button>
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-person"></i> Voltar ao perfil
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Conta protegida</h5>
                <p class="text-muted mb-0">
                    Use uma senha nova e evite repetir senhas usadas em outros sistemas. A alteração é aplicada imediatamente.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
