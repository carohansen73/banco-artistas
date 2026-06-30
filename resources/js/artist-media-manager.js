const fotosInput = document.getElementById('fotos');
const fotosPreview = document.getElementById('fotos-preview');
let selectedFotos = new DataTransfer();

// Previsualización de fotos seleccionadas
function renderFotosPreview() {
    fotosPreview.innerHTML = '';

    Array.from(selectedFotos.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const wrapper = document.createElement('div');
            wrapper.className = 'galeria-store-item position-relative';
            // wrapper.style.position = 'relative';
            // wrapper.style.display = 'inline-block';
            // wrapper.style.marginRight = '8px';
            // wrapper.style.marginBottom = '8px';


            wrapper.innerHTML = `
                <img src="${e.target.result}"
                    class="galeria-store-img">
                <button type="button" class="btn btn-sm btn-danger remove-foto"
                    data-index="${index}"
                    style="position:absolute; top:4px; right:4px; padding:0 6px; line-height:1;">
                    ×
                </button>`;

            fotosPreview.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });
}


// Agregar más de una foto
fotosInput.addEventListener('change', function () {
    Array.from(this.files).forEach(file => {
        selectedFotos.items.add(file);
    });
    fotosInput.files = selectedFotos.files;
    renderFotosPreview();
});

// Eliminar foto seleccionada del preview y del input
fotosPreview.addEventListener('click', function (event) {
    if (!event.target.classList.contains('remove-foto')) {
        return;
    }

    const indexToRemove = Number(event.target.dataset.index);
    const newData = new DataTransfer();

    Array.from(selectedFotos.files).forEach((file, index) => {
        if (index !== indexToRemove) {
            newData.items.add(file);
        }
    });

    selectedFotos = newData;
    fotosInput.files = selectedFotos.files;
    renderFotosPreview();
});

// Agregar track (audio)
document.getElementById('add-track').addEventListener('click', function () {
    const container = document.getElementById('tracks-container');
    container.insertAdjacentHTML('beforeend', `
        <div class="row track-row mb-3">
            <div class="col-sm-6 p-2">
                <input type="url" name="tracks[]" class="form-control"
                    placeholder="https://open.spotify.com/track/...">
            </div>
            <div class="col-sm-5 p-2">
                <input type="text" name="tracks_titulo[]" class="form-control"
                    placeholder="Título de la canción (opcional)">
            </div>
            <div class="col-sm-1 p-2 d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-row">✕</button>
            </div>
        </div>`);
});

// Agregar video
document.getElementById('add-video').addEventListener('click', function () {
    const container = document.getElementById('videos-container');
    container.insertAdjacentHTML('beforeend', `
        <div class="row video-row mb-3">
            <div class="col-sm-6 p-2">
                <input type="url" name="videos[]" class="form-control"
                    placeholder="https://www.youtube.com/watch?v=...">
            </div>
            <div class="col-sm-5 p-2">
                <input type="text" name="videos_titulo[]" class="form-control"
                    placeholder="Título del video (opcional)">
            </div>
            <div class="col-sm-1 p-2 d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-row">✕</button>
            </div>
        </div>`);
});

// Eliminar fila
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('.track-row, .video-row').remove();
    }
});
