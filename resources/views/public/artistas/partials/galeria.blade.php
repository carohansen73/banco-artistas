{{-- GALERÍA DE FOTOS --}}
@if($fotos->isNotEmpty())
<div class="mt-4">
    <div class="galeria-grid">
        @foreach($fotos as $foto)
            <div class="galeria-item" data-src="{{ asset('storage/' . $foto->url) }}"
                 data-titulo="{{ $foto->titulo }}">
                <img src="{{ asset('storage/' . $foto->url) }}" alt="{{ $foto->titulo }}">
                <div class="galeria-overlay"><i class="fas fa-expand"></i></div>
            </div>
        @endforeach
    </div>
</div>
@else
<div class="perfil-empty-state text-center text-muted mt-4">
    <i class="fas fa-images fa-2x mb-3 d-block"></i>
    <p class="mb-0">Todavía no hay fotos cargadas en la galería.</p>
</div>
@endif

<div class="lightbox-backdrop" id="lightbox">
    <button class="lightbox-cerrar" id="lightbox-cerrar">&times;</button>
    <button class="lightbox-nav lightbox-prev" id="lightbox-prev">&#8249;</button>
    <img class="lightbox-img" id="lightbox-img" src="" alt="">
    <button class="lightbox-nav lightbox-next" id="lightbox-next">&#8250;</button>
    <p class="lightbox-titulo" id="lightbox-titulo"></p>
</div>
