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

    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" href="{{ asset('icons/favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #0d6efd !important;
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
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-code-slash"></i> Portal DevTech
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
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
                    <div class="d-flex gap-2">
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
            const form = document.querySelector('[data-search-form]');

            if (!form) {
                return;
            }

            const input = form.querySelector('[data-search-input]');
            const suggestions = form.querySelector('[data-search-suggestions]');
            const endpoint = @json(route('busca.sugestoes'));
            let controller = null;
            let activeIndex = -1;
            let debounceTimer = null;

            const closeSuggestions = () => {
                suggestions.classList.remove('show');
                suggestions.innerHTML = '';
                activeIndex = -1;
            };

            const escapeHtml = (value) => value
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

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
        })();
    </script>
    @stack('scripts')
</body>
</html>
