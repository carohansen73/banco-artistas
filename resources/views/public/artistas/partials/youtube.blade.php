{{-- VIDEOS --}}
@if($videos->isNotEmpty())
<div class="mt-4">
    <h5 class="fw-bold text-red mb-3"></h5>
    <div class="row g-3">
        @foreach($videos as $video)
            <div class="col-md-6">
                <div class="ratio ratio-16x9">
                    <iframe src="{{ $video->embed_url }}"
                        title="{{ $video->titulo }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                @if($video->titulo)
                    <p class="text-muted small mt-1">{{ $video->titulo }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
@else
<div class="perfil-empty-state text-center text-muted mt-4">
    <i class="fab fa-youtube fa-2x mb-3 d-block"></i>
    <p class="mb-0">Todavía no hay videos cargados.</p>
</div>
@endif
