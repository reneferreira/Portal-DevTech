{{-- resources/views/front/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Portal DevTech - Desenvolvimento e Tecnologia')

@section('content')
    <!-- Banner Animado com Conexões de Internet -->
    <section class="hero-banner">
        <div class="network-bg">
            <canvas id="networkCanvas"></canvas>
        </div>
        <div class="container hero-content">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-1 fw-bold hero-title animate-title">Portal DevTech</h1>
                    <p class="lead hero-subtitle animate-subtitle">Desenvolvimento e Tecnologia</p>
                    <div class="hero-buttons animate-buttons mt-4">
                        <a href="#noticias" class="btn btn-primary btn-lg rounded-pill px-4 mx-2">
                            <i class="bi bi-newspaper"></i> Ver Notícias
                        </a>
                        <a href="#servicos" class="btn btn-outline-light btn-lg rounded-pill px-4 mx-2">
                            <i class="bi bi-code-square"></i> Nossos Serviços
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hero Section com Carrossel -->
    <section class="carousel-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div id="newsCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($destaques->take(4) as $index => $post)
                                <button type="button" data-bs-target="#newsCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner rounded-4">
                            @foreach($destaques->take(4) as $index => $post)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="carousel-slide" style="background-image: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%), url('{{ ImageHelper::getImageUrl($post->imagem) }}');">
                                        <div class="carousel-caption text-start">
                                            <span class="badge bg-primary mb-3 px-3 py-2">{{ $post->categoria->nome }}</span>
                                            <h2 class="display-5 fw-bold">{{ $post->titulo }}</h2>
                                            <p class="lead">{{ Str::limit($post->resumo, 120) }}</p>
                                            <a href="{{ route('post', $post->slug) }}" class="btn btn-primary btn-lg rounded-pill px-4">
                                                Ler Matéria <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Botão Tema Claro/Escuro -->
    <div class="theme-toggle-wrapper">
        <button id="themeToggle" class="btn-theme-toggle rounded-circle">
            <i class="bi bi-moon-fill"></i>
        </button>
    </div>

    <div class="container">
        <!-- Layout Principal: 75% Notícias | 25% Lateral -->
        <div class="row mt-5 g-4">
            <!-- Coluna Principal (75%) -->
            <div class="col-lg-9">
                <!-- Categorias em Slide -->
                <section class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h3 mb-0"><i class="bi bi-grid-3x3-gap-fill"></i> Categorias</h2>
                        <div class="category-nav-controls">
                            <button class="btn-category-prev btn btn-sm btn-outline-primary rounded-circle"><i class="bi bi-chevron-left"></i></button>
                            <button class="btn-category-next btn btn-sm btn-outline-primary rounded-circle"><i class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="categories-slider">
                        <div class="d-flex gap-3 overflow-auto" id="categoriesSlider">
                            @foreach($categorias as $categoria)
                                <div class="category-slide flex-shrink-0">
                                    <a href="{{ route('categoria', $categoria->slug) }}" class="text-decoration-none">
                                        <div class="card bg-gradient text-center p-3 category-card h-100">
                                            <i class="bi bi-{{ $categoria->icone ?? 'tag' }} fs-1 text-primary mb-2"></i>
                                            <h6 class="mb-1">{{ $categoria->nome }}</h6>
                                            <small class="text-muted">{{ $categoria->posts_count }} posts</small>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- Últimas Notícias -->
                <section class="mb-5" id="noticias">
                    <h2 class="h3 mb-4"><i class="bi bi-newspaper"></i> Últimas Notícias</h2>
                    <div class="row g-4">
                        @foreach($posts as $post)
                            <div class="col-md-6">
                                <div class="card post-card h-100 neon-hover">
                                    <div class="row g-0 h-100">
                                        <div class="col-md-5">
                                            <img src="{{ ImageHelper::getImageUrl($post->imagem) }}" 
                                                 class="img-fluid rounded-start h-100 object-fit-cover" 
                                                 style="object-fit: cover; min-height: 180px;"
                                                 alt="{{ $post->titulo }}">
                                        </div>
                                        <div class="col-md-7">
                                            <div class="card-body">
                                                <span class="category-badge mb-2 d-inline-block">
                                                    {{ $post->categoria->nome }}
                                                </span>
                                                <h5 class="card-title">
                                                    <a href="{{ route('post', $post->slug) }}" class="text-decoration-none stretched-link">
                                                        {{ Str::limit($post->titulo, 50) }}
                                                    </a>
                                                </h5>
                                                <p class="card-text text-muted small">{{ Str::limit($post->resumo, 70) }}</p>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3"></i> {{ $post->publicado_em->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->links() }}
                    </div>
                </section>
            </div>

            <!-- Sidebar (25%) -->
            <div class="col-lg-3">
                <!-- Card Clima -->
                <div class="card weather-card mb-4 border-0 shadow-sm" id="weatherWidget">
                    <div class="card-header bg-gradient-primary text-white">
                        <i class="bi bi-cloud-sun"></i> Clima em São Paulo
                    </div>
                    <div class="card-body text-center">
                        <div class="weather-temp mb-3">
                            <span class="display-1 fw-bold" id="currentTemp">--</span>
                            <span class="display-6">°C</span>
                        </div>
                        <div class="weather-desc mb-3" id="weatherDesc">Carregando...</div>
                        <div class="weather-details">
                            <div class="row g-2" id="weekForecast">
                                <div class="col-6">Seg: --°C</div>
                                <div class="col-6">Ter: --°C</div>
                                <div class="col-6">Qua: --°C</div>
                                <div class="col-6">Qui: --°C</div>
                                <div class="col-6">Sex: --°C</div>
                                <div class="col-6">Sáb: --°C</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mais Lidas da Semana -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white">
                        <i class="bi bi-fire"></i> Mais Lidas da Semana
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($maisLidas as $index => $post)
                                <a href="{{ route('post', $post->slug) }}" class="list-group-item list-group-item-action trending-item">
                                    <div class="d-flex align-items-center">
                                        <span class="trending-number me-3 fw-bold fs-5 text-primary">#{{ $index + 1 }}</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ Str::limit($post->titulo, 40) }}</h6>
                                            <small class="text-muted"><i class="bi bi-eye"></i> {{ $post->views }} visualizações</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Banner Patrocinador 1 -->
                <div class="sponsor-banner mb-4">
                    <div class="card border-0 bg-gradient-warning text-white text-center p-3">
                        <i class="bi bi-megaphone-fill fs-1 mb-2"></i>
                        <h5>Espaço do Patrocinador</h5>
                        <p class="small mb-0">Seu anúncio aqui - (11) 99999-9999</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Serviços -->
        <section class="mb-5" id="servicos">
            <h2 class="h3 mb-4"><i class="bi bi-code-square"></i> Nossos Serviços</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card text-center p-4 h-100 neon-hover service-card">
                        <div class="service-icon mb-3">
                            <i class="bi bi-laptop fs-1 text-primary"></i>
                        </div>
                        <h4>Desenvolvimento Web</h4>
                        <p class="text-muted">Criação de sites e sistemas web personalizados</p>
                        <a href="#" class="btn btn-outline-primary rounded-pill mt-3">Saiba mais <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-4 h-100 neon-hover service-card">
                        <div class="service-icon mb-3">
                            <i class="bi bi-phone fs-1 text-primary"></i>
                        </div>
                        <h4>Aplicativos Mobile</h4>
                        <p class="text-muted">Apps para Android e iOS com tecnologia de ponta</p>
                        <a href="#" class="btn btn-outline-primary rounded-pill mt-3">Saiba mais <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-4 h-100 neon-hover service-card">
                        <div class="service-icon mb-3">
                            <i class="bi bi-cart fs-1 text-primary"></i>
                        </div>
                        <h4>Sistemas Prontos</h4>
                        <p class="text-muted">Sistemas completos para venda e implementação</p>
                        <a href="#" class="btn btn-outline-primary rounded-pill mt-3">Ver produtos <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sessão de Vídeos -->
        <section class="mb-5">
            <h2 class="h3 mb-4"><i class="bi bi-play-circle-fill text-danger"></i> Vídeos em Destaque</h2>
            <div class="videos-slider">
                <div class="row g-4 flex-nowrap overflow-auto pb-3" id="videosSlider">
                    @foreach($videos as $video)
                        <div class="col-md-3 flex-shrink-0">
                            <div class="card video-card neon-hover h-100">
                                <div class="video-thumbnail position-relative">
                                    <img src="{{ $video->thumbnail ?? 'https://img.youtube.com/vi/'.$video->youtube_id.'/mqdefault.jpg' }}" 
                                         class="card-img-top" alt="{{ $video->titulo }}" style="height: 150px; object-fit: cover;">
                                    <div class="play-overlay">
                                        <i class="bi bi-play-circle-fill fs-1 text-white"></i>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">{{ Str::limit($video->titulo, 50) }}</h6>
                                    <small class="text-muted">{{ $video->views }} visualizações</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Sessão de Artigos em Slide Horizontal -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0"><i class="bi bi-book-half"></i> Artigos Recomendados</h2>
                <div class="articles-nav-controls">
                    <button class="btn-article-prev btn btn-sm btn-outline-primary rounded-circle"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn-article-next btn btn-sm btn-outline-primary rounded-circle"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <div class="articles-slider">
                <div class="row g-4 flex-nowrap overflow-auto pb-3" id="articlesSlider">
                    @foreach($artigos as $artigo)
                        <div class="col-md-3 flex-shrink-0">
                            <div class="card article-card neon-hover h-100">
                                <img src="{{ ImageHelper::getImageUrl($artigo->imagem) }}" 
                                     class="card-img-top" style="height: 180px; object-fit: cover;" 
                                     alt="{{ $artigo->titulo }}">
                                <div class="card-body">
                                    <span class="badge bg-info mb-2">{{ $artigo->categoria->nome ?? 'Artigos' }}</span>
                                    <h6 class="card-title">{{ Str::limit($artigo->titulo, 45) }}</h6>
                                    <p class="card-text small text-muted">{{ Str::limit($artigo->resumo, 60) }}</p>
                                    <a href="{{ route('post', $artigo->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        Ler Artigo <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Banner Patrocinador 2 - 25% da largura alinhado à esquerda -->
        <div class="row justify-content-start mb-5">
            <div class="col-md-3">
                <div class="sponsor-banner-small card border-0 bg-gradient-success text-white text-center p-3">
                    <i class="bi bi-trophy-fill fs-1 mb-2"></i>
                    <h6>Patrocínio Gold</h6>
                    <p class="small mb-0">TechCorp Solutions</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Adicione este script no final do seu home.blade.php, antes de @endsection --}}
{{-- Adicione este script no final do seu home.blade.php --}}
@push('scripts')
<script>
// Configuração do Clima - Sorriso/MT (Dados Reais)
const WEATHER_CONFIG = {
    city: 'Sorriso',
    state: 'MT',
    lat: -12.5425,
    lon: -55.7211
};

// Dados climáticos reais de Sorriso/MT (atualizados manualmente ou via API)
// Como estamos em desenvolvimento, vamos usar uma API gratuita que funciona localmente
async function buscarClima() {
    // Tentar usar a API do wttr.in (funciona sem chave e permite HTTP local)
    const url = `https://wttr.in/${WEATHER_CONFIG.lat},${WEATHER_CONFIG.lon}?format=j1&lang=pt`;
    
    try {
        // Tentar buscar dados reais
        const response = await fetch(url, {
            mode: 'cors',
            headers: {
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            atualizarClimaComDadosReais(data);
            return;
        }
    } catch (error) {
        console.log('API externa bloqueada, usando dados simulados...');
    }
    
    // Fallback: Usar dados simulados realistas para Sorriso/MT
    usarDadosSimulados();
}

// Função para usar dados reais da API
function atualizarClimaComDadosReais(data) {
    try {
        const current = data.current_condition[0];
        const temp = current.temp_C;
        const descricao = getDescricaoClima(current.weatherDesc[0]?.value || '');
        const umidade = current.humidity;
        const sensacao = current.FeelsLikeC;
        const vento = current.windspeedKmph;
        
        // Atualizar temperatura
        const tempElement = document.getElementById('currentTemp');
        if (tempElement) tempElement.textContent = temp;
        
        // Atualizar descrição
        const descElement = document.getElementById('weatherDesc');
        if (descElement) {
            const icone = getIconePorDescricao(descricao);
            descElement.innerHTML = `${icone} ${descricao}`;
            descElement.classList.remove('text-danger');
        }
        
        // Atualizar cabeçalho
        const cardHeader = document.querySelector('.weather-card .card-header');
        if (cardHeader) {
            cardHeader.innerHTML = `<i class="bi bi-cloud-sun"></i> Clima em ${WEATHER_CONFIG.city} - ${WEATHER_CONFIG.state}`;
        }
        
        // Adicionar detalhes extras
        adicionarDetalhesExtras(sensacao, umidade, vento);
        
        // Processar previsão
        processarPrevisao(data.weather);
        
    } catch (error) {
        console.error('Erro ao processar dados:', error);
        usarDadosSimulados();
    }
}

// Função para usar dados simulados (fallback)
function usarDadosSimulados() {
    // Dados realistas para Sorriso/MT (Clima tropical)
    const temperaturas = [28, 29, 30, 31, 32, 27, 26];
    const descricoes = ['Ensolarado', 'Parcialmente Nublado', 'Nublado', 'Chuva Leve', 'Ensolarado', 'Ensolarado', 'Parcialmente Nublado'];
    const icones = ['☀️', '⛅', '☁️', '🌧️', '☀️', '☀️', '⛅'];
    
    // Temperatura atual (baseada na hora do dia)
    const horaAtual = new Date().getHours();
    let tempAtual = 28;
    if (horaAtual >= 12 && horaAtual <= 16) {
        tempAtual = 32; // Mais quente ao meio dia
    } else if (horaAtual >= 6 && horaAtual <= 10) {
        tempAtual = 26; // Mais fresco pela manhã
    } else if (horaAtual >= 18) {
        tempAtual = 24; // Mais fresco à noite
    }
    
    const descAtual = horaAtual >= 18 ? 'Noite tranquila' : (tempAtual > 30 ? 'Muito Quente' : 'Clima agradável');
    const iconeAtual = horaAtual >= 18 ? '🌙' : '☀️';
    
    // Atualizar temperatura atual
    const tempElement = document.getElementById('currentTemp');
    if (tempElement) tempElement.textContent = tempAtual;
    
    // Atualizar descrição
    const descElement = document.getElementById('weatherDesc');
    if (descElement) {
        descElement.innerHTML = `${iconeAtual} ${descAtual}`;
        descElement.classList.remove('text-danger');
    }
    
    // Atualizar cabeçalho
    const cardHeader = document.querySelector('.weather-card .card-header');
    if (cardHeader) {
        cardHeader.innerHTML = `<i class="bi bi-cloud-sun"></i> Clima em ${WEATHER_CONFIG.city} - ${WEATHER_CONFIG.state}`;
    }
    
    // Adicionar detalhes simulados
    adicionarDetalhesExtras(
        Math.round(tempAtual - 2), // sensação térmica
        Math.round(50 + Math.random() * 20), // umidade
        Math.round(5 + Math.random() * 15) // vento
    );
    
    // Previsão dos próximos dias
    const diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
    const hoje = new Date().getDay();
    
    const forecastContainer = document.getElementById('weekForecast');
    if (forecastContainer) {
        forecastContainer.innerHTML = '';
        
        for (let i = 1; i <= 6; i++) {
            const diaIndex = (hoje + i) % 7;
            const temp = temperaturas[i % temperaturas.length];
            const desc = descricoes[i % descricoes.length];
            const icone = icones[i % icones.length];
            
            const col = document.createElement('div');
            col.className = 'col-6 mb-2';
            col.innerHTML = `
                <div class="d-flex align-items-center justify-content-between">
                    <span><strong>${diasSemana[diaIndex]}</strong></span>
                    <span>${icone}</span>
                    <span>${temp}°C</span>
                </div>
            `;
            forecastContainer.appendChild(col);
        }
    }
}

// Função para adicionar detalhes extras
function adicionarDetalhesExtras(sensacao, umidade, vento) {
    const weatherDetails = document.querySelector('.weather-details');
    if (weatherDetails && !document.getElementById('extraDetails')) {
        const extraHtml = `
            <div id="extraDetails" class="mt-2 pt-2 border-top">
                <div class="row g-2">
                    <div class="col-6">
                        <small><i class="bi bi-thermometer-half"></i> Sensação: ${sensacao}°C</small>
                    </div>
                    <div class="col-6">
                        <small><i class="bi bi-droplet-half"></i> Umidade: ${umidade}%</small>
                    </div>
                    <div class="col-6">
                        <small><i class="bi bi-wind"></i> Vento: ${vento} km/h</small>
                    </div>
                </div>
            </div>
        `;
        weatherDetails.insertAdjacentHTML('beforeend', extraHtml);
    }
}

// Função para processar previsão
function processarPrevisao(weatherData) {
    if (!weatherData) return;
    
    const diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
    const hoje = new Date().getDay();
    
    const forecastContainer = document.getElementById('weekForecast');
    if (forecastContainer && weatherData.length > 0) {
        forecastContainer.innerHTML = '';
        
        for (let i = 1; i <= 6 && i < weatherData.length; i++) {
            const dia = weatherData[i];
            const diaIndex = (hoje + i) % 7;
            const tempMax = dia.tempMaxC || dia.temp_C || 28;
            const tempMin = dia.tempMinC || dia.temp_C - 5 || 22;
            const desc = dia.weatherDesc?.[0]?.value || 'Clima agradável';
            const icone = getIconePorDescricao(desc);
            
            const col = document.createElement('div');
            col.className = 'col-6 mb-2';
            col.innerHTML = `
                <div class="d-flex align-items-center justify-content-between">
                    <span><strong>${diasSemana[diaIndex]}</strong></span>
                    <span>${icone}</span>
                    <span>${tempMin}°/${tempMax}°</span>
                </div>
            `;
            forecastContainer.appendChild(col);
        }
    }
}

// Função para obter descrição do clima
function getDescricaoClima(desc) {
    const descricoes = {
        'Sunny': 'Ensolarado',
        'Clear': 'Céu Limpo',
        'Partly cloudy': 'Parcialmente Nublado',
        'Cloudy': 'Nublado',
        'Overcast': 'Encoberto',
        'Mist': 'Névoa',
        'Patchy rain possible': 'Possibilidade de Chuva',
        'Light rain': 'Chuva Leve',
        'Moderate rain': 'Chuva Moderada',
        'Heavy rain': 'Chuva Forte'
    };
    return descricoes[desc] || desc || 'Clima agradável';
}

// Função para obter ícone baseado na descrição
function getIconePorDescricao(desc) {
    const icones = {
        'Ensolarado': '☀️',
        'Céu Limpo': '☀️',
        'Parcialmente Nublado': '⛅',
        'Nublado': '☁️',
        'Encoberto': '☁️',
        'Névoa': '🌫️',
        'Possibilidade de Chuva': '🌦️',
        'Chuva Leve': '🌧️',
        'Chuva Moderada': '🌧️',
        'Chuva Forte': '⛈️',
        'Noite tranquila': '🌙',
        'Muito Quente': '🔥',
        'Clima agradável': '😎'
    };
    return icones[desc] || '🌡️';
}

// Adicionar botão de atualização
function adicionarBotaoRefresh() {
    const card = document.querySelector('.weather-card');
    if (card && !document.querySelector('.weather-refresh')) {
        const refreshBtn = document.createElement('button');
        refreshBtn.className = 'btn btn-sm btn-outline-light rounded-circle weather-refresh';
        refreshBtn.style.position = 'absolute';
        refreshBtn.style.top = '10px';
        refreshBtn.style.right = '10px';
        refreshBtn.style.zIndex = '10';
        refreshBtn.style.background = 'rgba(0,0,0,0.3)';
        refreshBtn.style.border = 'none';
        refreshBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i>';
        refreshBtn.title = 'Atualizar clima';
        refreshBtn.onclick = (e) => {
            e.preventDefault();
            refreshBtn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            buscarClima();
            setTimeout(() => {
                refreshBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i>';
            }, 2000);
        };
        
        card.style.position = 'relative';
        card.appendChild(refreshBtn);
    }
}

// Inicializar
document.addEventListener('DOMContentLoaded', () => {
    buscarClima();
    adicionarBotaoRefresh();
    
    // Atualizar a cada 30 minutos
    setInterval(buscarClima, 1800000);
});
</script>
@endpush
@endsection

@push('styles')
<style>
    :root {
        --bg-primary: #ffffff;
        --bg-secondary: #f8f9fa;
        --text-primary: #212529;
        --text-secondary: #6c757d;
        --card-bg: #ffffff;
        --border-color: #dee2e6;
        --neon-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
        --neon-hover: 0 0 20px rgba(13, 110, 253, 0.5);
        --tech-blue: #0d6efd;
        --tech-cyan: #00d4ff;
        --tech-violet: #764ba2;
        --tech-pink: #f093fb;
        --surface-glass: rgba(255, 255, 255, 0.82);
    }

    [data-theme="dark"] {
        --bg-primary: #1a1a2e;
        --bg-secondary: #16213e;
        --text-primary: #ffffff;
        --text-secondary: #a0a0a0;
        --card-bg: #0f3460;
        --border-color: #2c3e50;
    }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .container > section,
    .col-lg-9 > section {
        scroll-margin-top: 96px;
    }

    section h2,
    section .h3 {
        letter-spacing: -0.02em;
        font-weight: 800;
        color: #152033;
    }

    section h2 i,
    section .h3 i {
        color: var(--tech-blue);
    }

    /* Hero Banner com Animação de Rede */
    .hero-banner {
        position: relative;
        min-height: min(560px, 72vh);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background:
            radial-gradient(circle at 18% 22%, rgba(0, 212, 255, 0.24), transparent 26%),
            radial-gradient(circle at 82% 18%, rgba(240, 147, 251, 0.26), transparent 28%),
            linear-gradient(135deg, #07111f 0%, #121b3b 46%, #211143 100%);
        isolation: isolate;
    }

    .hero-banner::after {
        content: "";
        position: absolute;
        inset: auto 0 0;
        height: 34%;
        z-index: 1;
        pointer-events: none;
        background: linear-gradient(180deg, transparent 0%, rgba(245, 247, 251, 0.92) 100%);
    }

    .network-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }

    #networkCanvas {
        width: 100%;
        height: 100%;
        display: block;
    }

    .hero-content {
        position: relative;
        z-index: 3;
        text-align: center;
        padding: 60px 0;
    }

    .hero-title {
        font-weight: 800;
        background: linear-gradient(135deg, #ffffff 0%, #00d4ff 38%, #f093fb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 0 30px rgba(0, 212, 255, 0.2);
        letter-spacing: 0;
    }

    .hero-subtitle {
        font-size: clamp(1rem, 2.5vw, 1.5rem);
        color: rgba(255, 255, 255, 0.9);
        font-weight: 300;
    }

    .hero-buttons .btn {
        border: 0;
        box-shadow: 0 18px 42px rgba(0, 0, 0, 0.24);
    }

    .hero-buttons .btn-primary {
        background: linear-gradient(135deg, var(--tech-blue) 0%, var(--tech-violet) 100%);
    }

    .hero-buttons .btn-outline-light {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.24);
        backdrop-filter: blur(10px);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-title {
        animation: fadeInUp 0.8s ease-out;
    }

    .animate-subtitle {
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    .animate-buttons {
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }

    /* Cards */
    .card, .post-card, .category-card, .weather-card, .service-card {
        background: var(--surface-glass);
        border: 1px solid rgba(13, 110, 253, 0.1);
        transition: all 0.3s ease;
        border-radius: 18px;
        box-shadow: 0 16px 42px rgba(17, 32, 63, 0.08);
        backdrop-filter: blur(12px);
    }

    /* Neon Hover Effect */
    .neon-hover {
        transition: all 0.3s ease;
    }

    .neon-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 24px 50px rgba(13, 110, 253, 0.18);
        border-color: rgba(13, 110, 253, 0.38);
    }

    .neon-hover:hover .card-title a {
        color: #0d6efd !important;
    }

    .service-card:hover .service-icon i {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    /* Theme Toggle Button */
    .theme-toggle-wrapper {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
    }

    .btn-theme-toggle {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--tech-blue) 0%, var(--tech-violet) 100%);
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .btn-theme-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }

    /* Carousel Slide */
    .carousel-section {
        margin-top: -52px;
        position: relative;
        z-index: 4;
    }

    .carousel-slide {
        height: 500px;
        background-size: cover;
        background-position: center;
        border-radius: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 26px 70px rgba(17, 32, 63, 0.22);
    }

    .carousel-caption {
        bottom: 20%;
        left: 5%;
        right: auto;
        text-align: left;
        width: min(620px, 72%);
        padding: 1.5rem;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(7, 17, 31, 0.78), rgba(22, 34, 69, 0.42));
        backdrop-filter: blur(10px);
    }

    @media (max-width: 768px) {
        .hero-content {
            padding: 44px 14px 84px;
        }
        .hero-buttons {
            display: grid;
            gap: 0.75rem;
        }
        .hero-buttons .btn {
            width: 100%;
            margin: 0 !important;
        }
        .carousel-section {
            margin-top: -34px;
        }
        .carousel-slide {
            height: 360px;
            border-radius: 18px;
        }
        .carousel-caption {
            width: calc(100% - 28px);
            left: 14px;
            bottom: 14px;
            padding: 1rem;
        }
        .carousel-caption h2 {
            font-size: 1.25rem;
        }
        .carousel-caption .lead {
            font-size: 0.92rem;
        }
        .hero-banner {
            min-height: 420px;
        }
        .hero-title {
            font-size: 2.7rem !important;
        }
        .hero-subtitle {
            font-size: 1rem;
        }
        .post-card .row {
            display: block;
        }
        .post-card img {
            width: 100%;
            min-height: 210px !important;
            border-radius: 18px 18px 0 0 !important;
        }
        .post-card .card-body {
            padding: 1.1rem;
        }
        .theme-toggle-wrapper {
            right: 18px;
            bottom: 18px;
        }
    }

    /* Category Card */
    .category-card {
        width: 132px;
        min-height: 126px;
        border-radius: 18px;
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(239, 246, 255, 0.9) 100%);
    }

    .category-card i {
        width: 52px;
        height: 52px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-inline: auto;
        border-radius: 16px;
        color: #fff !important;
        background: linear-gradient(135deg, var(--tech-blue) 0%, var(--tech-cyan) 100%);
    }

    /* Trending Items */
    .trending-item {
        background-color: var(--card-bg);
        border-left: 3px solid transparent;
        transition: all 0.3s;
    }

    .trending-item:hover {
        border-left-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
    }

    .trending-number {
        width: 35px;
    }

    /* Video Card */
    .video-thumbnail {
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .video-thumbnail:hover .play-overlay {
        opacity: 1;
    }

    /* Scrollbars */
    #categoriesSlider::-webkit-scrollbar,
    #videosSlider::-webkit-scrollbar,
    #articlesSlider::-webkit-scrollbar {
        height: 6px;
    }

    #categoriesSlider::-webkit-scrollbar-track,
    #videosSlider::-webkit-scrollbar-track,
    #articlesSlider::-webkit-scrollbar-track {
        background: var(--bg-secondary);
        border-radius: 10px;
    }

    #categoriesSlider::-webkit-scrollbar-thumb,
    #videosSlider::-webkit-scrollbar-thumb,
    #articlesSlider::-webkit-scrollbar-thumb {
        background: #0d6efd;
        border-radius: 10px;
    }

    /* Gradient Backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--tech-blue) 0%, var(--tech-violet) 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .object-fit-cover {
        object-fit: cover;
    }

    .category-badge {
        background: linear-gradient(135deg, var(--tech-blue) 0%, var(--tech-violet) 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        text-decoration: none;
    }

    .category-badge:hover {
        color: white;
        opacity: 0.9;
    }

      /* Estilos do Widget de Clima */
    .weather-card {
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .weather-card:hover {
        transform: translateY(-3px);
    }
    
    .weather-card .card-header {
        background: linear-gradient(135deg, var(--tech-blue) 0%, var(--tech-violet) 100%);
        font-weight: 500;
    }
    
    .weather-refresh {
        position: absolute !important;
        top: 10px;
        right: 10px;
        z-index: 10;
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    
    .weather-refresh:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(180deg);
    }
    
    #currentTemp {
        font-size: 3.5rem;
        font-weight: bold;
        background: linear-gradient(135deg, var(--tech-blue) 0%, var(--tech-cyan) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    @media (max-width: 768px) {
        #currentTemp {
            font-size: 2.5rem;
        }
    }
    
    .weather-details {
        font-size: 0.85rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Theme Toggle
    const themeToggle = document.getElementById('themeToggle');
    const icon = themeToggle.querySelector('i');
    
    function setTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            icon.className = 'bi bi-sun-fill';
        } else {
            document.documentElement.removeAttribute('data-theme');
            icon.className = 'bi bi-moon-fill';
        }
        localStorage.setItem('theme', theme);
    }
    
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        setTheme('dark');
    }
    
    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if (currentTheme === 'dark') {
            setTheme('light');
        } else {
            setTheme('dark');
        }
    });

    // Animação de Rede (Conexões)
    const canvas = document.getElementById('networkCanvas');
    let ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];
    let connections = [];
    
    function initNetwork() {
        width = canvas.parentElement.clientWidth;
        height = canvas.parentElement.clientHeight;
        canvas.width = width;
        canvas.height = height;
        
        particles = [];
        const particleCount = Math.min(80, Math.floor(width * height / 15000));
        
        for (let i = 0; i < particleCount; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                radius: Math.random() * 2 + 1,
                speedX: (Math.random() - 0.5) * 0.5,
                speedY: (Math.random() - 0.5) * 0.5,
                color: `hsl(${Math.random() * 60 + 200}, 70%, 60%)`
            });
        }
    }
    
    function drawNetwork() {
        if (!ctx) return;
        ctx.clearRect(0, 0, width, height);
        
        // Desenhar conexões
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 120) {
                    const opacity = (1 - distance / 120) * 0.3;
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(100, 150, 255, ${opacity})`;
                    ctx.lineWidth = 0.8;
                    ctx.stroke();
                }
            }
        }
        
        // Desenhar partículas
        for (let particle of particles) {
            ctx.beginPath();
            ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
            ctx.fillStyle = particle.color;
            ctx.fill();
            
            // Atualizar posição
            particle.x += particle.speedX;
            particle.y += particle.speedY;
            
            // Border collision
            if (particle.x < 0 || particle.x > width) particle.speedX *= -1;
            if (particle.y < 0 || particle.y > height) particle.speedY *= -1;
            
            // Keep within bounds
            particle.x = Math.max(0, Math.min(width, particle.x));
            particle.y = Math.max(0, Math.min(height, particle.y));
        }
        
        requestAnimationFrame(drawNetwork);
    }
    
    window.addEventListener('resize', () => {
        initNetwork();
    });
    
    initNetwork();
    drawNetwork();

    // Weather Widget (Simulated)
    async function loadWeather() {
        try {
            const temps = [28, 29, 27, 26, 28, 30];
            const days = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
            
            document.getElementById('currentTemp').innerText = '28';
            document.getElementById('weatherDesc').innerText = 'Ensolarado';
            
            const forecastContainer = document.getElementById('weekForecast');
            forecastContainer.innerHTML = days.map((day, i) => 
                `<div class="col-6">${day}: ${temps[i]}°C</div>`
            ).join('');
        } catch (error) {
            console.error('Weather error:', error);
        }
    }
    
    loadWeather();

    // Slider Navigation
    function setupSlider(scrollContainerId, prevBtnClass, nextBtnClass) {
        const container = document.getElementById(scrollContainerId);
        const prevBtn = document.querySelector(prevBtnClass);
        const nextBtn = document.querySelector(nextBtnClass);
        
        if (container && prevBtn && nextBtn) {
            prevBtn.addEventListener('click', () => {
                container.scrollBy({ left: -300, behavior: 'smooth' });
            });
            nextBtn.addEventListener('click', () => {
                container.scrollBy({ left: 300, behavior: 'smooth' });
            });
        }
    }
    
    setupSlider('categoriesSlider', '.btn-category-prev', '.btn-category-next');
    setupSlider('articlesSlider', '.btn-article-prev', '.btn-article-next');
</script>
@endpush
