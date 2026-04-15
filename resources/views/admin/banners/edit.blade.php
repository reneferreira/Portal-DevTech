{{-- resources/views/admin/banners/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Editar Banner')
@section('header', 'Editar Banner')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Editar Banner</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label class="form-label">Título <span class="text-danger">*</span></label>
                                <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" 
                                       value="{{ old('titulo', $banner->titulo) }}" required>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Descrição</label>
                                <textarea name="descricao" class="form-control @error('descricao') is-invalid @enderror" 
                                          rows="3">{{ old('descricao', $banner->descricao) }}</textarea>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Link (URL)</label>
                                <input type="url" name="link" class="form-control @error('link') is-invalid @enderror" 
                                       value="{{ old('link', $banner->link) }}" placeholder="https://exemplo.com">
                                <small class="text-muted">Para onde o banner vai redirecionar quando clicado</small>
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Tipo do Banner <span class="text-danger">*</span></label>
                                <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" id="tipoBanner" required>
                                    <option value="imagem" {{ old('tipo', $banner->tipo) == 'imagem' ? 'selected' : '' }}>Imagem</option>
                                    <option value="html" {{ old('tipo', $banner->tipo) == 'html' ? 'selected' : '' }}>Código HTML</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3" id="campoImagem">
                                @if($banner->imagem_url)
                                    <div class="mb-2">
                                        <label class="form-label">Imagem Atual</label>
                                        <div class="text-center p-2 bg-light rounded">
                                            <img src="{{ $banner->imagem_url }}" class="img-fluid rounded" style="max-height: 100px;">
                                        </div>
                                    </div>
                                @endif
                                
                                <label class="form-label">Alterar Imagem</label>
                                <input type="file" name="imagem" class="form-control @error('imagem') is-invalid @enderror" 
                                       accept="image/*">
                                <small class="text-muted">Formatos: JPG, PNG, GIF (máx. 2MB)</small>
                                @error('imagem')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3 d-none" id="campoHtml">
                                <label class="form-label">Código HTML <span class="text-danger">*</span></label>
                                <textarea name="html_code" class="form-control @error('html_code') is-invalid @enderror" 
                                          rows="6" placeholder='&lt;a href="https://exemplo.com"&gt;&lt;img src="banner.jpg"&gt;&lt;/a&gt;'>{{ old('html_code', $banner->html_code) }}</textarea>
                                <small class="text-muted">Cole o código HTML do banner (Google Ads, etc.)</small>
                                @error('html_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Posição <span class="text-danger">*</span></label>
                                <select name="posicao" class="form-select @error('posicao') is-invalid @enderror" required>
                                    <option value="topo" {{ old('posicao', $banner->posicao) == 'topo' ? 'selected' : '' }}>Topo do Site</option>
                                    <option value="sidebar" {{ old('posicao', $banner->posicao) == 'sidebar' ? 'selected' : '' }}>Sidebar (Lateral)</option>
                                    <option value="entre_posts" {{ old('posicao', $banner->posicao) == 'entre_posts' ? 'selected' : '' }}>Entre Posts</option>
                                    <option value="footer" {{ old('posicao', $banner->posicao) == 'footer' ? 'selected' : '' }}>Footer (Rodapé)</option>
                                </select>
                                @error('posicao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Ordem</label>
                                <input type="number" name="ordem" class="form-control @error('ordem') is-invalid @enderror" 
                                       value="{{ old('ordem', $banner->ordem) }}" min="0">
                                <small class="text-muted">Menor número = mais alto</small>
                                @error('ordem')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Data de Início</label>
                                <input type="date" name="data_inicio" class="form-control @error('data_inicio') is-invalid @enderror" 
                                       value="{{ old('data_inicio', $banner->data_inicio ? $banner->data_inicio->format('Y-m-d') : '') }}">
                                <small class="text-muted">Deixe em branco para iniciar imediatamente</small>
                                @error('data_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Data de Término</label>
                                <input type="date" name="data_fim" class="form-control @error('data_fim') is-invalid @enderror" 
                                       value="{{ old('data_fim', $banner->data_fim ? $banner->data_fim->format('Y-m-d') : '') }}">
                                <small class="text-muted">Deixe em branco para não expirar</small>
                                @error('data_fim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="ativo" class="form-check-input" id="ativo" value="1" {{ old('ativo', $banner->ativo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ativo">
                                    <i class="bi bi-check-circle"></i> Ativo
                                </label>
                                <small class="text-muted d-block">Se desmarcado, o banner não será exibido</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($banner->imagem_url || $banner->html_code)
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="mb-2">Preview Atual:</h6>
                        <div class="text-center">
                            @if($banner->imagem_url)
                                <img src="{{ $banner->imagem_url }}" class="img-fluid rounded" style="max-height: 150px;">
                            @elseif($banner->html_code)
                                {!! $banner->html_code !!}
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-3 p-3 bg-light rounded" id="previewArea" style="display: none;">
                        <h6 class="mb-2">Novo Preview:</h6>
                        <div id="previewContent" class="text-center"></div>
                    </div>
                    
                    <div class="text-end mt-4">
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Atualizar Banner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const tipoSelect = document.getElementById('tipoBanner');
    const campoImagem = document.getElementById('campoImagem');
    const campoHtml = document.getElementById('campoHtml');
    const previewArea = document.getElementById('previewArea');
    const previewContent = document.getElementById('previewContent');
    
    function toggleCampos() {
        const tipo = tipoSelect.value;
        
        if (tipo === 'imagem') {
            campoImagem.classList.remove('d-none');
            campoHtml.classList.add('d-none');
        } else {
            campoImagem.classList.add('d-none');
            campoHtml.classList.remove('d-none');
        }
    }
    
    tipoSelect.addEventListener('change', toggleCampos);
    toggleCampos();
    
    // Preview da nova imagem
    document.querySelector('input[name="imagem"]')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewContent.innerHTML = `<img src="${event.target.result}" class="img-fluid rounded" style="max-height: 150px;">`;
                previewArea.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Preview do HTML
    document.querySelector('textarea[name="html_code"]')?.addEventListener('input', function() {
        const html = this.value;
        if (html) {
            previewContent.innerHTML = html;
            previewArea.style.display = 'block';
        }
    });
</script>
@endpush
@endsection