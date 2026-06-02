{{-- SPOTIFY --}}
@if($audios->isNotEmpty())
<div class="mt-4">


    <div class="d-flex flex-column gap-3">
        @foreach($audios as $audio)
            <iframe src="{{ $audio->embed_url }}"
                width="100%"
                height="152"
                frameborder="0"
                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                loading="lazy"
                style="border-radius: 12px;">
            </iframe>
        @endforeach
    </div>
</div>
@endif
