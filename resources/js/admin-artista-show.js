/**
 * admin-artista-show.js
 * Tabs sin reload + eliminación AJAX de media + visibility toggle
 */

document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // TABS
    // =========================================================
    const TAB_KEY = 'admin_artista_show_tab';

    const tabBtns    = document.querySelectorAll('.admin-tab-btn');
    const tabPanels  = document.querySelectorAll('.admin-tab-content');

    function activarTab(nombre) {
        tabBtns.forEach(btn => {
            const isActive = btn.dataset.tab === nombre;
            btn.classList.toggle('border-indigo-500', isActive);
            btn.classList.toggle('dark:border-indigo-400', isActive);
            btn.classList.toggle('text-indigo-600', isActive);
            btn.classList.toggle('dark:text-indigo-400', isActive);
            btn.classList.toggle('border-transparent', !isActive);
            btn.classList.toggle('text-gray-500', !isActive);
            btn.classList.toggle('dark:text-gray-400', !isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');

            // Badge del tab activo: resaltarlo en indigo
            const badge = btn.querySelector('.admin-tab-badge');
            if (badge) {
                badge.classList.toggle('bg-indigo-100', isActive);
                badge.classList.toggle('dark:bg-indigo-900/50', isActive);
                badge.classList.toggle('text-indigo-700', isActive);
                badge.classList.toggle('dark:text-indigo-300', isActive);
                badge.classList.toggle('bg-gray-100', !isActive);
                badge.classList.toggle('dark:bg-gray-700', !isActive);
                badge.classList.toggle('text-gray-600', !isActive);
                badge.classList.toggle('dark:text-gray-400', !isActive);
            }
        });

        tabPanels.forEach(panel => {
            const show = panel.id === 'admin-tab-' + nombre;
            panel.classList.toggle('hidden', !show);
        });
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const tab = this.dataset.tab;
            activarTab(tab);
            sessionStorage.setItem(TAB_KEY, tab);
        });
    });

    // Tab inicial: sessionStorage o 'info'
    const tabInicial = sessionStorage.getItem(TAB_KEY) || 'info';
    activarTab(tabInicial);


    // =========================================================
    // VISIBILITY TOGGLE
    // =========================================================
    const toggle = document.querySelector('[data-visibility-toggle]');
    if (toggle) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        toggle.addEventListener('change', async function () {
            const url   = this.dataset.url;
            const label = document.querySelector('[data-visibility-label]');
            const previousChecked = !this.checked;
            this.disabled = true;

            try {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ visible: this.checked }),
                });

                if (!response.ok) throw new Error();


                const data = await response.json();
                this.checked = Boolean(data.visible);
                if (label) {
                    label.textContent = data.visible ? 'Visible' : 'Oculto';
                }
                mostrarToast(data.visible ? 'Artista publicado.' : 'Artista ocultado.');
            } catch {
                // Revertir si falla
                this.checked = previousChecked;
                mostrarToast('Error al cambiar la visibilidad.', 'error');
            } finally {
                this.disabled = false;
            }
        });
    }


    // =========================================================
    // MODAL CONFIRMAR ELIMINAR
    // =========================================================
    const modal     = document.getElementById('modal-eliminar');
    const backdrop  = document.getElementById('modal-backdrop');
    const btnCancelar  = document.getElementById('modal-cancelar');
    const btnConfirmar = document.getElementById('modal-confirmar');

    let deleteUrl    = null;
    let deleteItemEl = null;

    function abrirModal() { modal.classList.remove('hidden'); }
    function cerrarModal() {
        modal.classList.add('hidden');
        deleteUrl    = null;
        deleteItemEl = null;
    }

    // Click en botón eliminar de cualquier media-item
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-media');
        if (!btn) return;
        deleteUrl    = btn.dataset.url;
        deleteItemEl = btn.closest('.media-item');
        abrirModal();
    });

    backdrop.addEventListener('click', cerrarModal);
    btnCancelar.addEventListener('click', cerrarModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) cerrarModal();
    });

    btnConfirmar.addEventListener('click', function () {
        if (!deleteUrl) return;

        // Guarda referencias ANTES de cerrar el modal para poder quitarlo de la vista
        // porque sino se pierde al eliminarlo.
        const url    = deleteUrl;
        const itemEl = deleteItemEl;


        btnConfirmar.disabled = true;
        btnConfirmar.textContent = 'Eliminando…';

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            cerrarModal();

            if (data.success) {
                if (itemEl) {
                    itemEl.style.transition = 'opacity 0.25s';
                    itemEl.style.opacity = '0';
                    setTimeout(() => {
                        itemEl.remove();
                        actualizarBadges();
                    }, 250);
                }
                mostrarToast('Elemento eliminado.');
            } else {
                mostrarToast('No se pudo eliminar. Intentá de nuevo.', 'error');
            }
        })
        .catch(() => {
            cerrarModal();
            mostrarToast('Error de conexión.', 'error');
        })
        .finally(() => {
            btnConfirmar.disabled = false;
            btnConfirmar.textContent = 'Eliminar';
        });
    });


    // =========================================================
    // ACTUALIZAR BADGES DE TABS TRAS ELIMINACIÓN
    // =========================================================
    function actualizarBadges() {
        const mapa = {
            galeria: { contenedor: 'galeria-grid',  selector: '.media-item' },
            videos:  { contenedor: 'videos-lista',  selector: '.media-item' },
            spotify: { contenedor: 'tracks-lista',  selector: '.media-item' },
        };

        Object.entries(mapa).forEach(([tabKey, config]) => {
            const contenedor = document.getElementById(config.contenedor);
            if (!contenedor) return;

            const count = contenedor.querySelectorAll(config.selector).length;
            const btn   = document.querySelector(`.admin-tab-btn[data-tab="${tabKey}"]`);
            if (!btn) return;

            const badge = btn.querySelector('.admin-tab-badge');
            if (badge) badge.textContent = count;
        });
    }


    // =========================================================
    // TOAST
    // =========================================================
    function mostrarToast(mensaje, tipo = 'success') {
        const prev = document.getElementById('admin-show-toast');
        if (prev) prev.remove();

        const colores = {
            success: 'bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900',
            error:   'bg-red-600 text-white',
        };

        const toast = document.createElement('div');
        toast.id = 'admin-show-toast';
        toast.style.cssText = `
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
        `;
        toast.innerHTML = `
            <div class="rounded-lg shadow-lg px-5 py-2.5 text-sm font-medium ${colores[tipo] ?? colores.success}">
                ${mensaje}
            </div>`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity 0.4s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, 2800);
    }

});
