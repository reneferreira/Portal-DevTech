{{-- resources/views/front/post.blade.php --}}
@extends('layouts.app')

@section('title', $post->titulo . ' - Portal DevTech')

@section('content')
    <div class="container py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categoria', $post->categoria->slug) }}">{{ $post->categoria->nome }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $post->titulo }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8">
                <!-- Post Principal -->
                <article class="card border-0 shadow-sm mb-4">
                    <img src="{{ ImageHelper::getImageUrl($post->imagem) }}" 
                        class="card-img-top" 
                        alt="{{ $post->titulo }}" 
                        style="max-height: 500px; object-fit: cover;">
                    
                    <div class="card-body p-4">
                        <h1 class="display-5 fw-bold mb-3">{{ $post->titulo }}</h1>
                        
                        <div class="d-flex align-items-center mb-4 text-muted">
                            <div class="me-3">
                                <i class="bi bi-person-circle"></i> {{ $post->user->name ?? 'Admin' }}
                            </div>
                            <div class="me-3">
                                <i class="bi bi-calendar3"></i> {{ $post->publicado_em->format('d/m/Y H:i') }}
                            </div>
                            <div>
                                <i class="bi bi-eye"></i> {{ $post->views }} visualizações
                            </div>
                        </div>

                        <!-- Tags -->
                        @if($post->tags->count() > 0)
                            <div class="mb-4">
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('tag', $tag->slug) }}" class="category-badge me-2">
                                        <i class="bi bi-tag"></i> {{ $tag->nome }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Resumo -->
                        @if($post->resumo)
                            <div class="lead fst-italic border-start border-primary border-3 ps-3 mb-4">
                                {{ $post->resumo }}
                            </div>
                        @endif

                        <!-- Conteúdo -->
                        <div class="post-content">
                            {!! $post->conteudo !!}
                        </div>

                        <!-- Compartilhar -->
                        <div class="mt-4 pt-3 border-top">
                            <h5>Compartilhe:</h5>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                                   target="_blank" class="btn btn-outline-primary rounded-circle">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->titulo) }}" 
                                   target="_blank" class="btn btn-outline-info rounded-circle">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}" 
                                   target="_blank" class="btn btn-outline-secondary rounded-circle">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->titulo . ' - ' . request()->url()) }}" 
                                   target="_blank" class="btn btn-outline-success rounded-circle">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Se for um produto/sistema -->
                @if($post->categoria->nome == 'Sistemas' || $post->categoria->nome == 'Produtos')
                    <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
                        <div class="card-body p-4">
                            <h4 class="mb-3"><i class="bi bi-cart"></i> Interessado neste sistema?</h4>
                            <p>Entre em contato conosco para mais informações ou para adquirir este produto.</p>
                            <a href="{{ route('contato', ['post_id' => $post->id]) }}" class="btn btn-light btn-lg rounded-pill">
                                <i class="bi bi-envelope"></i> Solicitar orçamento
                            </a>
                        </div>
                    </div>
                @endif

             <!-- Comentários -->
        <div class="card border-0 shadow-sm" id="comentarios">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-chat-dots"></i> 
                        Comentários ({{ $post->comments->count() }})
                    </h4>
                    <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="scrollToForm()">
                        <i class="bi bi-plus-circle"></i> Deixar comentário
                    </button>
                </div>

                <!-- Lista de Comentários -->
                @if($post->comments->count() > 0)
                    <div class="comments-list mb-4">
                        @foreach($post->comments as $comment)
                            <div class="comment-item d-flex mb-4" id="comment-{{ $comment->id }}">
                                <div class="flex-shrink-0">
                                    <div class="comment-avatar bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-person-circle fs-2 text-secondary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="comment-content bg-light p-3 rounded-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <strong class="comment-author">{{ $comment->nome }}</strong>
                                                @if($comment->is_novo)
                                                    <span class="badge bg-success ms-2">Novo</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3"></i> {{ $comment->data_formatada }}
                                            </small>
                                        </div>
                                        <p class="comment-text mb-0">{{ nl2br(e($comment->comentario)) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="bi bi-chat-square-text"></i> 
                        Seja o primeiro a comentar neste post!
                    </div>
                @endif

                <!-- Formulário de Comentário -->
                <div class="comment-form mt-4 p-4 bg-light rounded-3" id="comment-form">
                    <h5 class="mb-3">
                        <i class="bi bi-pencil-square"></i> Deixe seu comentário
                    </h5>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('comentario.store', $post->id) }}" method="POST" id="formComentario">
                        @csrf
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
                                <small class="text-muted">Seu email não será publicado</small>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Comentário *</label>
                                <textarea name="comentario" class="form-control @error('comentario') is-invalid @enderror" 
                                          rows="4" required>{{ old('comentario') }}</textarea>
                                <div class="form-text">
                                    <span id="charCount">0</span> / 1000 caracteres
                                </div>
                                @error('comentario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Recaptcha (opcional - recomendo adicionar depois) -->
                            {{-- <div class="col-12 mb-3">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            </div> --}}
                            
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4" id="btnEnviar">
                                        <i class="bi bi-send"></i> Enviar comentário
                                    </button>
                                    <button type="reset" class="btn btn-secondary rounded-pill px-4">
                                        <i class="bi bi-eraser"></i> Limpar
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle"></i> 
                                    Todos os comentários são moderados e serão publicados após aprovação.
                                </small>
                            </div>
                        </div>
                    </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Posts Relacionados -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="mb-0"><i class="bi bi-link"></i> Posts Relacionados</h5>
                    </div>
                    <div class="card-body">
                        @foreach($relacionados as $relacionado)
                            <div class="d-flex mb-3">
                                <img src="{{ ImageHelper::getThumbnailUrl($relacionado) }}" 
                                     class="rounded me-3" width="80" height="80" style="object-fit: cover;" alt="">
                                <div>
                                    <h6>
                                        <a href="{{ route('post', $relacionado->slug) }}" class="text-dark text-decoration-none">
                                            {{ $relacionado->titulo }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $relacionado->publicado_em->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Categorias -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="mb-0"><i class="bi bi-grid"></i> Categorias</h5>
                    </div>
                    <div class="card-body">
                        @foreach($categorias as $categoria)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <a href="{{ route('categoria', $categoria->slug) }}" class="text-decoration-none">
                                    <i class="bi bi-{{ $categoria->icone ?? 'tag' }}"></i> {{ $categoria->nome }}
                                </a>
                                <span class="badge bg-secondary rounded-pill">{{ $categoria->posts_count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body p-4">
                        <h5 class="mb-3"><i class="bi bi-envelope"></i> Newsletter</h5>
                        <p>Receba as novidades no seu email</p>
                        <form action="{{ route('newsletter') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Seu melhor email" required>
                            </div>
                            <button type="submit" class="btn btn-light w-100 rounded-pill">
                                Assinar newsletter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Contador de caracteres
    const textarea = document.querySelector('textarea[name="comentario"]');
    const charCount = document.getElementById('charCount');
    
    if (textarea) {
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 900) {
                charCount.classList.add('text-warning');
            } else if (count > 950) {
                charCount.classList.add('text-danger');
            } else {
                charCount.classList.remove('text-warning', 'text-danger');
            }
        });
        
        // Trigger inicial
        charCount.textContent = textarea.value.length;
    }
    
    // Scroll para o formulário
    function scrollToForm() {
        document.getElementById('comment-form').scrollIntoView({ 
            behavior: 'smooth' 
        });
    }
    
    // Validação client-side antes de enviar
    document.getElementById('formComentario')?.addEventListener('submit', function(e) {
        const nome = document.querySelector('input[name="nome"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        const comentario = document.querySelector('textarea[name="comentario"]').value.trim();
        
        if (nome.length < 3) {
            e.preventDefault();
            alert('O nome deve ter no mínimo 3 caracteres.');
            return false;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Digite um email válido.');
            return false;
        }
        
        if (comentario.length < 5) {
            e.preventDefault();
            alert('O comentário deve ter no mínimo 5 caracteres.');
            return false;
        }
        
        if (comentario.length > 1000) {
            e.preventDefault();
            alert('O comentário não pode ter mais de 1000 caracteres.');
            return false;
        }
        
        // Desabilitar botão para evitar múltiplos envios
        const btn = document.getElementById('btnEnviar');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enviando...';
    });
</script>
@endpush

@push('styles')
<style>
    .comment-item {
        transition: transform 0.2s;
    }
    .comment-item:hover {
        transform: translateX(5px);
    }
    .comment-content {
        transition: background 0.2s;
    }
    .comment-item:hover .comment-content {
        background: #e9ecef !important;
    }
    .comment-avatar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    textarea {
        resize: vertical;
    }
</style>
@endpush
