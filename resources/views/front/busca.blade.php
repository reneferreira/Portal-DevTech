@extends('layouts.app')

@section('title', 'Busca por "' . $query . '" - Portal DevTech')

@push('styles')
<style>
    mark.search-highlight {
        background: rgba(13, 110, 253, 0.16);
        color: inherit;
        padding: 0.1rem 0.35rem;
        border-radius: 0.4rem;
    }
</style>
@endpush

@section('content')
<section class="py-5">
    <div class="container">
        @php
            $highlight = function (?string $text) use ($searchTerms) {
                if (! $text) {
                    return new \Illuminate\Support\HtmlString('');
                }

                $escapedText = e($text);

                foreach ($searchTerms as $term) {
                    $escapedTerm = preg_quote(e($term), '/');
                    $escapedText = preg_replace(
                        "/($escapedTerm)/iu",
                        '<mark class="search-highlight">$1</mark>',
                        $escapedText
                    );
                }

                return new \Illuminate\Support\HtmlString($escapedText);
            };
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">
                            Busca
                        </span>
                        <h1 class="h3 mb-1">Resultados para "{{ $query }}"</h1>
                        <p class="text-muted mb-0">
                            {{ $posts->total() }} {{ \Illuminate\Support\Str::plural('notícia', $posts->total()) }} encontrada(s).
                        </p>
                        @if($searchTerms->isNotEmpty())
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                @foreach($searchTerms as $term)
                                    <span class="badge rounded-pill text-bg-light border">{{ $term }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <form class="d-flex" action="{{ route('busca') }}" method="GET">
                        <input
                            class="form-control me-2 rounded-pill"
                            type="search"
                            name="q"
                            value="{{ $query }}"
                            placeholder="Buscar notícias..."
                            required
                        >
                        <button class="btn btn-primary rounded-pill px-4" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                @if(session('warning'))
                    <div class="alert alert-warning rounded-4">
                        {{ session('warning') }}
                    </div>
                @endif

                @if($posts->count())
                    <div class="row g-4">
                        @foreach($posts as $post)
                            <div class="col-12">
                                <article class="card post-card h-100">
                                    <div class="row g-0">
                                        @if($post->imagem)
                                            <div class="col-md-4">
                                                <img
                                                    src="{{ ImageHelper::getImageUrl($post->imagem) }}"
                                                    alt="{{ $post->titulo }}"
                                                    class="img-fluid h-100 w-100"
                                                >
                                            </div>
                                        @endif

                                        <div class="{{ $post->imagem ? 'col-md-8' : 'col-12' }}">
                                            <div class="card-body p-4">
                                                <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                                    <a href="{{ route('categoria', $post->categoria->slug) }}" class="category-badge">
                                                        {!! $highlight($post->categoria->nome) !!}
                                                    </a>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar3"></i>
                                                        {{ optional($post->publicado_em)->format('d/m/Y') }}
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="bi bi-eye"></i>
                                                        {{ $post->views }} visualizações
                                                    </small>
                                                </div>

                                                <h2 class="h4 mb-3">
                                                    <a href="{{ route('post', $post->slug) }}" class="text-dark text-decoration-none">
                                                        {!! $highlight($post->titulo) !!}
                                                    </a>
                                                </h2>

                                                @if($post->resumo)
                                                    <p class="text-muted mb-3">{!! $highlight($post->resumo) !!}</p>
                                                @endif

                                                @if($post->tags->isNotEmpty())
                                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                                        @foreach($post->tags as $tag)
                                                            <span class="badge rounded-pill text-bg-light">
                                                                # {!! $highlight($tag->nome) !!}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <a href="{{ route('post', $post->slug) }}" class="btn btn-outline-primary rounded-pill">
                                                    Ler notícia
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-5 text-center">
                            <div class="display-6 text-muted mb-3">
                                <i class="bi bi-search"></i>
                            </div>
                            <h2 class="h4 mb-2">Nenhuma notícia encontrada</h2>
                            <p class="text-muted mb-4">
                                Tente buscar por outro termo, categoria ou palavra-chave.
                            </p>
                            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4">
                                Voltar para a home
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
