{{-- resources/views/front/contato.blade.php --}}
@extends('layouts.app')

@section('title', 'Contato - Portal DevTech')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold">Entre em Contato</h1>
                    <p class="lead text-muted">Estamos aqui para ajudar com suas dúvidas, projetos ou orçamentos</p>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card text-center p-4 border-0 shadow-sm">
                            <i class="bi bi-geo-alt fs-1 text-primary mb-3"></i>
                            <h5>Endereço</h5>
                            <p class="text-muted mb-0">Av. Tecnologia, 1000<br>São Paulo, SP</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-4 border-0 shadow-sm">
                            <i class="bi bi-envelope fs-1 text-primary mb-3"></i>
                            <h5>Email</h5>
                            <p class="text-muted mb-0">contato@technews.com.br<br>suporte@technews.com.br</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-4 border-0 shadow-sm">
                            <i class="bi bi-telephone fs-1 text-primary mb-3"></i>
                            <h5>Telefone</h5>
                            <p class="text-muted mb-0">(11) 99999-9999<br>(11) 3333-3333</p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="mb-4"><i class="bi bi-chat-dots"></i> Envie sua mensagem</h4>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('contato.enviar') }}" method="POST">
                            @csrf
                            
                            @if(request()->has('post_id'))
                                <input type="hidden" name="post_id" value="{{ request()->post_id }}">
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nome *</label>
                                    <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" 
                                           value="{{ old('nome') }}" required>
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror" 
                                           value="{{ old('telefone') }}" placeholder="(11) 99999-9999">
                                    @error('telefone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Assunto *</label>
                                    <select name="assunto" class="form-select @error('assunto') is-invalid @enderror" required>
                                        <option value="">Selecione...</option>
                                        <option value="Informações sobre serviços" {{ old('assunto') == 'Informações sobre serviços' ? 'selected' : '' }}>
                                            Informações sobre serviços
                                        </option>
                                        <option value="Orçamento de sistema" {{ old('assunto') == 'Orçamento de sistema' ? 'selected' : '' }}>
                                            Orçamento de sistema
                                        </option>
                                        <option value="Suporte" {{ old('assunto') == 'Suporte' ? 'selected' : '' }}>
                                            Suporte
                                        </option>
                                        <option value="Parcerias" {{ old('assunto') == 'Parcerias' ? 'selected' : '' }}>
                                            Parcerias
                                        </option>
                                        <option value="Outros" {{ old('assunto') == 'Outros' ? 'selected' : '' }}>
                                            Outros
                                        </option>
                                    </select>
                                    @error('assunto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Mensagem *</label>
                                    <textarea name="mensagem" class="form-control @error('mensagem') is-invalid @enderror" 
                                              rows="5" required>{{ old('mensagem') }}</textarea>
                                    @error('mensagem')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                                        <i class="bi bi-send"></i> Enviar mensagem
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection