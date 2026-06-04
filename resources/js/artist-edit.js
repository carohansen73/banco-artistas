/**
 * artist-edit.js
 * Maneja: tabs, eliminación AJAX de media, preview de foto de perfil,
 * preview de galería nueva, géneros dinámicos, formación condicional,
 * agregar/quitar filas de tracks y videos.
 */

document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // TABS
    // =========================================================
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    // Leer tab activo desde sessionStorage (para persistir tras redirect)
    const savedTab = sessionStorage.getItem('artista_edit_tab') || 'info';
    activarTab(savedTab);

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const tab = this.dataset.tab;
            activarTab(tab);
            sessionStorage.setItem('artista_edit_tab', tab);
        });
    });

    function activarTab(nombre) {
        tabBtns.forEach(b => {
            b.classList.toggle('active', b.dataset.tab === nombre);
        });
        tabContents.forEach(c => {
            c.style.display = c.id === 'tab-' + nombre ? '' : 'none';
        });
    }


    // =========================================================
    // GÉNEROS DINÁMICOS
    // =========================================================
    const disciplinaSelect = document.getElementById('disciplina_id');

    if (disciplinaSelect) {
        disciplinaSelect.addEventListener('change', function () {
            cargarGeneros(this.value, []);
        });

        // Si ya hay disciplina seleccionada al cargar, mostrar géneros
        // (ya vienen renderizados desde el blade con los activos marcados,
        //  pero si el usuario cambia la disciplina, se recargan via fetch)
    }

    function cargarGeneros(disciplinaId, activos) {
        const container = document.getElementById('generos-container');
        const lista = document.getElementById('generos-lista');

        if (!disciplinaId) {
            container.style.display = 'none';
            lista.innerHTML = '';
            return;
        }

        fetch(`/api/generos/${disciplinaId}`)
            .then(r => r.json())
            .then(generos => {
                lista.innerHTML = '';
                if (generos.length === 0) {
                    container.style.display = 'none';
                    return;
                }
                generos.forEach(g => {
                    const checked = activos.includes(g.id) ? 'checked' : '';
                    lista.innerHTML += `
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox"
                                name="generos[]" value="${g.id}"
                                id="genero_${g.id}" ${checked}>
                            <label class="form-check-label" for="genero_${g.id}">
                                ${g.nombre}
                            </label>
                        </div>`;
                });
                container.style.display = 'block';
            });
    }


    // =========================================================
    // FORMACIÓN CONDICIONAL
    // =========================================================
    const tieneFormacion = document.getElementById('tiene_formacion');
    if (tieneFormacion) {
        tieneFormacion.addEventListener('change', function () {
            const container = document.getElementById('detalle-formacion-container');
            container.style.display = this.value === '1' ? '' : 'none';
        });
    }


    // =========================================================
    // PREVIEW FOTO DE PERFIL
    // =========================================================
    const imgPerfilInput = document.getElementById('img_perfil');
    if (imgPerfilInput) {
        imgPerfilInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('img-preview').src = e.target.result;
                    document.getElementById('preview-container').style.display = '';
                };
                reader.readAsDataURL(file);
            }
        });
    }


    // =========================================================
    // PREVIEW GALERÍA (fotos nuevas)
    // =========================================================
    const fotosInput = document.getElementById('fotos');
    const fotosPreview = document.getElementById('fotos-preview');
    let selectedFotos = new DataTransfer();

    if (fotosInput) {
        fotosInput.addEventListener('change', function () {
            Array.from(this.files).forEach(file => {
                selectedFotos.items.add(file);
            });
            fotosInput.files = selectedFotos.files;
            renderFotosPreview();
        });
    }

    if (fotosPreview) {
        fotosPreview.addEventListener('click', function (e) {
            if (!e.target.classList.contains('remove-foto')) return;

            const idx = Number(e.target.dataset.index);
            const newData = new DataTransfer();
            Array.from(selectedFotos.files).forEach((file, i) => {
                if (i !== idx) newData.items.add(file);
            });
            selectedFotos = newData;
            fotosInput.files = selectedFotos.files;
            renderFotosPreview();
        });
    }

    function renderFotosPreview() {
        if (!fotosPreview) return;
        fotosPreview.innerHTML = '';
        Array.from(selectedFotos.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrapper = document.createElement('div');
                wrapper.className = 'galeria-store-item position-relative';
                wrapper.innerHTML = `
                    <img src="${e.target.result}"
                        class="galeria-store-img">
                    <button type="button" class="btn btn-sm btn-danger remove-foto"
                        data-index="${index}"
                        style="position:absolute; top:4px; right:4px; padding:0 6px; line-height:1.6;">
                        ×
                    </button>`;
                fotosPreview.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }


    // =========================================================
    // AGREGAR / QUITAR FILAS DE TRACKS Y VIDEOS
    // =========================================================
    const addTrack = document.getElementById('add-track');
    if (addTrack) {
        addTrack.addEventListener('click', function () {
            document.getElementById('tracks-container').insertAdjacentHTML('beforeend', `
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
    }

    const addVideo = document.getElementById('add-video');
    if (addVideo) {
        addVideo.addEventListener('click', function () {
            document.getElementById('videos-container').insertAdjacentHTML('beforeend', `
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
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.track-row, .video-row').remove();
        }
    });


    // =========================================================
    // ELIMINACIÓN AJAX DE MEDIA
    // =========================================================
    let deleteUrl = null;
    let deleteItemEl = null;
    const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));

    // Captura el click en cualquier botón de eliminar (fotos, videos, audios)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-media');
        if (!btn) return;

        deleteUrl = btn.dataset.url;
        deleteItemEl = btn.closest('.media-item');
        modal.show();
    });

    // Confirma eliminación
    document.getElementById('btn-confirmar-eliminar').addEventListener('click', function () {
        if (!deleteUrl) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                modal.hide();

                // Animación de salida y eliminación del DOM
                if (deleteItemEl) {
                    deleteItemEl.style.transition = 'opacity 0.3s';
                    deleteItemEl.style.opacity = '0';
                    setTimeout(() => {
                        deleteItemEl.remove();
                        actualizarContadores();
                    }, 300);
                }

                mostrarToast('Elemento eliminado correctamente.');
            } else {
                mostrarToast('Hubo un error al eliminar. Intentá nuevamente.', 'danger');
            }
        })
        .catch(() => {
            mostrarToast('Error de conexión. Intentá nuevamente.', 'danger');
        })
        .finally(() => {
            deleteUrl = null;
            deleteItemEl = null;
        });
    });


    // =========================================================
    // HELPERS
    // =========================================================

    /**
     * Actualiza los badges de cantidad en los tabs
     * después de eliminar un elemento.
     */
    function actualizarContadores() {
        const tabs = {
            fotos:  { container: 'galeria-fotos',  btnTab: 'fotos'  },
            videos: { container: 'lista-videos',   btnTab: 'videos' },
            audios: { container: 'lista-audios',   btnTab: 'audios' },
        };

        Object.entries(tabs).forEach(([, config]) => {
            const container = document.getElementById(config.container);
            if (!container) return;

            const count = container.querySelectorAll('.media-item').length;
            const btn = document.querySelector(`.tab-btn[data-tab="${config.btnTab}"]`);
            if (!btn) return;

            const badge = btn.querySelector('.badge');
            if (count > 0) {
                if (badge) {
                    badge.textContent = count;
                } else {
                    btn.insertAdjacentHTML('beforeend',
                        `<span class="badge bg-secondary ms-1">${count}</span>`);
                }
            } else {
                badge?.remove();
            }
        });
    }

    /**
     * Muestra un toast flotante al pie de la pantalla.
     */
    function mostrarToast(mensaje, tipo = 'success') {
        const existing = document.getElementById('edit-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'edit-toast';
        toast.style.cssText = `
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 260px;
            text-align: center;
        `;
        toast.innerHTML = `
            <div class="alert alert-${tipo} shadow mb-0 py-2 px-4 rounded-pill">
                ${mensaje}
            </div>`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity 0.4s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, 2800);
    }

    // Auto-ocultar el alert de success de Laravel tras 3s
    const alertSuccess = document.getElementById('alert-success');
    if (alertSuccess) {
        setTimeout(() => {
            alertSuccess.style.transition = 'opacity 0.5s';
            alertSuccess.style.opacity = '0';
            setTimeout(() => alertSuccess.remove(), 500);
        }, 3000);
    }

});
