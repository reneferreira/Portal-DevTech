{{-- resources/views/front/categoria.blade.php --}}
@foreach($posts as $post)
    <div class="col-md-4 mb-4">
        <div class="card post-card h-100">
            <img src="{{ $post->imagem ? Storage::url($post->imagem) : asset('images/placeholder.jpg') }}" 
                 class="card-img-top" 
                 alt="{{ $post->titulo }}">
            {{-- resto do conteúdo --}}
        </div>
    </div>
@endforeach