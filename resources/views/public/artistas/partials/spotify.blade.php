{{-- SPOTIFY --}}
@if($artista->tracks->isNotEmpty())
<div class="mt-4">


    <div class="d-flex flex-column gap-3">
        @foreach($artista->tracks as $track)
            @php
                preg_match('/(track|playlist|artist)\/([A-Za-z0-9]+)/', $track->url, $m);
                $spotifyTipo = $m[1] ?? null;
                $spotifyId   = $m[2] ?? null;
            @endphp

            @if ($spotifyId)
                <div class="spotify-track-item">
                    @if ($track->titulo)
                        <p class="small text-muted mb-1 ps-1">{{ $track->titulo }}</p>
                    @endif
                    <iframe
                        src="https://open.spotify.com/embed/{{ $spotifyTipo }}/{{ $spotifyId  }}"
                        width="100%"
                        height="{{ $spotifyTipo === 'playlist' ? '352' : '80' }}"
                        frameborder="0"
                        allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                        loading="lazy"
                        style="border-radius: 12px;">
                    </iframe>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endif


