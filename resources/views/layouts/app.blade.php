{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d6efd">
    <meta name="push-public-key-url" content="{{ route('push.public-key') }}">
    <meta name="push-subscribe-url" content="{{ route('push.subscribe') }}">
    <meta name="push-unsubscribe-url" content="{{ route('push.unsubscribe') }}">

    <title>@yield('title', 'Portal DevTech - Portal de Tecnologia')</title>

    @include('partials.pwa-head')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            color: #152033;
            overflow-x: hidden;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background:
                radial-gradient(circle at 12% 10%, rgba(13, 110, 253, 0.12), transparent 28%),
                radial-gradient(circle at 88% 0%, rgba(118, 75, 162, 0.12), transparent 26%),
                linear-gradient(180deg, #ffffff 0%, #f5f7fb 52%, #eef3ff 100%);
        }
        .site-navbar {
            border: 1px solid rgba(255, 255, 255, 0.72);
            background: rgba(255, 255, 255, 0.88) !important;
            backdrop-filter: blur(18px);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #12213f !important;
            letter-spacing: -0.02em;
        }
        .navbar-brand i,
        .brand-gradient {
            background: linear-gradient(135deg, #0d6efd 0%, #764ba2 70%, #f093fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .navbar .nav-link {
            border-radius: 999px;
            color: #41516d;
            font-weight: 600;
            padding: 0.55rem 0.85rem !important;
            transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease;
        }
        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.08);
            transform: translateY(-1px);
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 2rem;
        }
        .post-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .post-card img {
            height: 200px;
            object-fit: cover;
        }
        .category-badge {
            background: #e9ecef;
            color: #495057;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.8rem;
            transition: background 0.3s;
        }
        .category-badge:hover {
            background: #0d6efd;
            color: white;
        }
        .footer {
            background: #212529;
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }
        .btn-primary {
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
        }
        .user-dropdown .dropdown-toggle::after {
            display: none;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .search-form {
            position: relative;
            width: min(100%, 360px);
        }
        .search-input {
            padding-right: 3rem;
        }
        .search-submit {
            min-width: 46px;
            border: 0;
            color: white;
            background: linear-gradient(135deg, #0d6efd 0%, #764ba2 100%);
            box-shadow: 0 10px 24px rgba(13, 110, 253, 0.2);
        }
        .search-suggestions {
            position: absolute;
            top: calc(100% + 0.5rem);
            left: 0;
            right: 0;
            z-index: 1080;
            display: none;
            padding: 0.5rem;
            background: #fff;
            border: 1px solid rgba(13, 110, 253, 0.12);
            border-radius: 1rem;
            box-shadow: 0 16px 40px rgba(13, 25, 48, 0.14);
        }
        .search-suggestions.show {
            display: block;
        }
        .search-suggestion-item {
            display: block;
            padding: 0.8rem 0.9rem;
            border-radius: 0.85rem;
            color: #212529;
            text-decoration: none;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .search-suggestion-item:hover,
        .search-suggestion-item.active {
            background: #f3f7ff;
            color: #0d6efd;
            transform: translateY(-1px);
        }
        .search-suggestion-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 0.35rem;
        }
        .search-suggestion-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.55rem;
            border-radius: 999px;
            font-size: 0.75rem;
            background: #eef2f7;
            color: #52606d;
        }
        .nav-cta-group .btn,
        .btn-primary {
            border: 0;
            background-image: linear-gradient(135deg, #0d6efd 0%, #764ba2 100%);
            box-shadow: 0 12px 28px rgba(13, 110, 253, 0.2);
        }
        .nav-cta-group .btn-outline-primary,
        .nav-cta-group .btn-outline-secondary {
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(13, 110, 253, 0.16);
            color: #0d6efd;
            box-shadow: none;
        }
        .mobile-nav-panel .nav-cta-group .btn-light {
            color: #16213e;
            background: #fff;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.14);
        }
        .mobile-nav-panel .nav-cta-group .btn-outline-light {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.22);
            box-shadow: none;
        }
        .mobile-menu-button {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 999px;
            color: #fff;
            background: linear-gradient(135deg, #0d6efd 0%, #764ba2 100%);
            box-shadow: 0 14px 34px rgba(13, 110, 253, 0.28);
        }
        .mobile-menu-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.18);
        }
        .mobile-nav-panel {
            width: min(88vw, 380px) !important;
            border: 0;
            color: #fff;
            background:
                linear-gradient(160deg, rgba(8, 18, 42, 0.97) 0%, rgba(29, 54, 104, 0.98) 45%, rgba(118, 75, 162, 0.96) 100%);
            box-shadow: -28px 0 70px rgba(13, 25, 48, 0.34);
        }
        .mobile-nav-panel.offcanvas-end {
            transform: translateX(105%);
        }
        .mobile-nav-panel.show:not(.hiding),
        .mobile-nav-panel.showing {
            transform: none;
        }
        .mobile-nav-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 22% 12%, rgba(79, 172, 254, 0.32), transparent 30%),
                radial-gradient(circle at 92% 88%, rgba(240, 147, 251, 0.24), transparent 32%);
        }
        .mobile-nav-panel .offcanvas-header,
        .mobile-nav-panel .offcanvas-body {
            position: relative;
            z-index: 1;
        }
        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.95rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            background: rgba(255, 255, 255, 0.08);
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
        }
        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.16);
            border-color: rgba(255, 255, 255, 0.22);
            transform: translateX(-4px);
        }
        .mobile-category-list {
            max-height: 230px;
            overflow: auto;
            padding-right: 0.2rem;
        }
        .mobile-category-list::-webkit-scrollbar {
            width: 5px;
        }
        .mobile-category-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.28);
            border-radius: 999px;
        }
        .mobile-nav-panel .search-form {
            width: 100%;
        }
        .mobile-nav-panel .search-input {
            border: 0;
            background: rgba(255, 255, 255, 0.94);
        }
        .mobile-nav-panel .search-suggestions {
            border: 0;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.24);
        }
        @media (max-width: 991.98px) {
            .site-navbar .container {
                min-height: 68px;
            }
            .navbar-brand {
                font-size: 1.15rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top site-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-code-slash"></i> Portal DevTech
            </a>
            <button class="mobile-menu-button d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-label="Abrir menu">
                <i class="bi bi-list fs-3"></i>
            </button>
            <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-grid"></i> Categorias
                        </a>
                        <ul class="dropdown-menu">
                            @php
                                $categorias = \App\Models\Categoria::withCount('posts')->get();
                            @endphp
                            @foreach($categorias as $categoria)
                                <li>
                                    <a class="dropdown-item" href="{{ route('categoria', $categoria->slug) }}">
                                        <i class="bi bi-{{ $categoria->icone ?? 'tag' }}"></i>
                                        {{ $categoria->nome }}
                                        <span class="badge bg-secondary rounded-pill float-end">{{ $categoria->posts_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#servicos"><i class="bi bi-code"></i> Serviços</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-cart"></i> Loja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contato') }}"><i class="bi bi-envelope"></i> Contato</a>
                    </li>
                </ul>

                <form class="d-flex me-3 search-form" action="{{ route('busca') }}" method="GET" autocomplete="off" data-search-form>
                    <input class="form-control me-2 rounded-pill search-input" type="search" name="q" placeholder="Buscar notícias..." data-search-input aria-label="Buscar notícias">
                    <button class="btn btn-outline-primary rounded-pill search-submit" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    <div class="search-suggestions" data-search-suggestions></div>
                </form>

                @auth
                    <div class="dropdown user-dropdown">
                        <button class="btn btn-link text-decoration-none dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <div class="user-avatar d-inline-flex">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text">
                                    <strong>{{ auth()->user()->name }}</strong><br>
                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>

                            @if(auth()->user()->is_admin)
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Painel Admin
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.posts.index') }}">
                                        <i class="bi bi-newspaper"></i> Gerenciar Posts
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="d-flex gap-2 nav-cta-group">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-pwa-enable>
                            Ativar notificacoes
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill">
                            <i class="bi bi-box-arrow-in-right"></i> 
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary rounded-pill">
                            <i class="bi bi-person-plus"></i> 
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end mobile-nav-panel" tabindex="-1" id="mobileNav" aria-labelledby="mobileNavLabel">
        <div class="offcanvas-header px-4 pt-4">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-code-slash fs-3"></i>
                    <h5 class="offcanvas-title fw-bold mb-0" id="mobileNavLabel">Portal DevTech</h5>
                </div>
                <small class="text-white-50">Noticias, tecnologia e servicos digitais.</small>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>
        <div class="offcanvas-body px-4 pb-4">
            <form class="d-flex search-form mb-4" action="{{ route('busca') }}" method="GET" autocomplete="off" data-search-form>
                <input class="form-control rounded-pill search-input" type="search" name="q" placeholder="Buscar noticias..." data-search-input aria-label="Buscar noticias">
                <button class="btn rounded-pill search-submit ms-2" type="submit" aria-label="Buscar">
                    <i class="bi bi-search"></i>
                </button>
                <div class="search-suggestions" data-search-suggestions></div>
            </form>

            <div class="d-grid gap-2 mb-4">
                <a class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="bi bi-house-door"></i> Home
                </a>
                <a class="mobile-nav-link" href="#servicos" data-bs-dismiss="offcanvas">
                    <i class="bi bi-code"></i> Servicos
                </a>
                <a class="mobile-nav-link" href="#">
                    <i class="bi bi-cart"></i> Loja
                </a>
                <a class="mobile-nav-link {{ request()->routeIs('contato') ? 'active' : '' }}" href="{{ route('contato') }}">
                    <i class="bi bi-envelope"></i> Contato
                </a>
            </div>

            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-semibold text-white-50 text-uppercase small">Categorias</span>
                    <i class="bi bi-grid text-white-50"></i>
                </div>
                <div class="mobile-category-list d-grid gap-2">
                    @foreach($categorias as $categoria)
                        <a class="mobile-nav-link" href="{{ route('categoria', $categoria->slug) }}">
                            <i class="bi bi-{{ $categoria->icone ?? 'tag' }}"></i>
                            <span class="flex-grow-1">{{ $categoria->nome }}</span>
                            <span class="badge rounded-pill text-bg-light">{{ $categoria->posts_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            @auth
                <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.1);">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <small class="text-white-50">{{ auth()->user()->email }}</small>
                    <div class="d-grid gap-2 mt-3">
                        @if(auth()->user()->is_admin)
                            <a class="btn btn-light rounded-pill" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Painel Admin
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light rounded-pill w-100">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="d-grid gap-2 nav-cta-group">
                    <button type="button" class="btn btn-outline-light rounded-pill" data-pwa-enable>
                        <i class="bi bi-bell"></i> Ativar notificacoes
                    </button>
                    <a href="{{ route('login') }}" class="btn btn-light rounded-pill">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary rounded-pill">
                        <i class="bi bi-person-plus"></i> Criar conta
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="bi bi-code-slash"></i> Portal DevTech</h5>
                    <p>Seu portal de notícias e serviços de tecnologia. Fique por dentro das últimas novidades do mundo tech.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-linkedin fs-4"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Links Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50 text-decoration-none">Sobre Nós</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Política de Privacidade</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Termos de Uso</a></li>
                        <li><a href="{{ route('contato') }}" class="text-white-50 text-decoration-none">Contato</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Categorias</h5>
                    <ul class="list-unstyled">
                        @foreach($categorias->take(5) as $categoria)
                            <li><a href="{{ route('categoria', $categoria->slug) }}" class="text-white-50 text-decoration-none">{{ $categoria->nome }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Newsletter</h5>
                    <p>Receba as últimas notícias no seu email</p>
                    <form action="{{ route('newsletter') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Seu email" required>
                            <button class="btn btn-primary" type="submit">Assinar</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center text-white-50">
                <p class="mb-0">&copy; {{ date('Y') }} TechNews. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/pwa.js') }}"></script>
    <script>
        (() => {
            const forms = document.querySelectorAll('[data-search-form]');

            if (!forms.length) {
                return;
            }

            const endpoint = @json(route('busca.sugestoes'));

            const escapeHtml = (value) => value
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

            forms.forEach((form) => {
                const input = form.querySelector('[data-search-input]');
                const suggestions = form.querySelector('[data-search-suggestions]');
                let controller = null;
                let activeIndex = -1;
                let debounceTimer = null;

                if (!input || !suggestions) {
                    return;
                }

                const closeSuggestions = () => {
                    suggestions.classList.remove('show');
                    suggestions.innerHTML = '';
                    activeIndex = -1;
                };

                const updateActiveItem = () => {
                    const items = suggestions.querySelectorAll('[data-suggestion-item]');

                    items.forEach((item, index) => {
                        item.classList.toggle('active', index === activeIndex);
                    });
                };

                const renderSuggestions = (items) => {
                    if (!items.length) {
                        closeSuggestions();
                        return;
                    }

                    suggestions.innerHTML = items.map((item, index) => {
                        const category = item.category
                            ? `<span class="search-suggestion-pill">${escapeHtml(item.category)}</span>`
                            : '';

                        const tags = (item.tags || [])
                            .map((tag) => `<span class="search-suggestion-pill">#${escapeHtml(tag)}</span>`)
                            .join('');

                        return `
                            <a href="${item.url}" class="search-suggestion-item" data-suggestion-item data-index="${index}">
                                <strong>${escapeHtml(item.title)}</strong>
                                <div class="search-suggestion-meta">
                                    ${category}
                                    ${tags}
                                </div>
                            </a>
                        `;
                    }).join('');

                    suggestions.classList.add('show');
                    activeIndex = -1;
                };

                const fetchSuggestions = async (value) => {
                    if (value.trim().length < 2) {
                        closeSuggestions();
                        return;
                    }

                    if (controller) {
                        controller.abort();
                    }

                    controller = new AbortController();

                    try {
                        const response = await fetch(`${endpoint}?q=${encodeURIComponent(value)}`, {
                            signal: controller.signal,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            closeSuggestions();
                            return;
                        }

                        renderSuggestions(await response.json());
                    } catch (error) {
                        if (error.name !== 'AbortError') {
                            closeSuggestions();
                        }
                    }
                };

                input.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => fetchSuggestions(input.value), 180);
                });

                input.addEventListener('focus', () => {
                    if (suggestions.innerHTML.trim() !== '') {
                        suggestions.classList.add('show');
                    }
                });

                input.addEventListener('keydown', (event) => {
                    const items = suggestions.querySelectorAll('[data-suggestion-item]');

                    if (!items.length) {
                        return;
                    }

                    if (event.key === 'ArrowDown') {
                        event.preventDefault();
                        activeIndex = (activeIndex + 1) % items.length;
                        updateActiveItem();
                    }

                    if (event.key === 'ArrowUp') {
                        event.preventDefault();
                        activeIndex = activeIndex <= 0 ? items.length - 1 : activeIndex - 1;
                        updateActiveItem();
                    }

                    if (event.key === 'Enter' && activeIndex >= 0) {
                        event.preventDefault();
                        items[activeIndex].click();
                    }

                    if (event.key === 'Escape') {
                        closeSuggestions();
                    }
                });

                document.addEventListener('click', (event) => {
                    if (!form.contains(event.target)) {
                        closeSuggestions();
                    }
                });
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
