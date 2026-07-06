import {
    confirmAlert,
    successAlert,
    errorAlert
} from './utils/notifications';
import { showToast } from './utils/flash-toast';

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

                if (!response.ok) {
                    let detalle = '';
                    try {
                        const errBody = await response.json();
                        detalle = errBody.message || JSON.stringify(errBody);
                    } catch {
                        detalle = await response.text();
                    }
                    console.error('Error del servidor:', response.status, detalle);
                    throw new Error(detalle);
                }

                const data = await response.json();
                this.checked = Boolean(data.visible);
                if (label) {
                    label.textContent = data.visible ? 'Visible' : 'Oculto';
                }

                // Toast importado
                showToast(
                        'success',
                        data.visible
                            ? 'Artista publicado correctamente.'
                            : 'Artista ocultado correctamente.'
                    );

            } catch {
                // Revertir si falla
                this.checked = previousChecked;
                await errorAlert(
                    'No se pudo actualizar',
                    'Error al cambiar la visibilidad.'
                );
            } finally {
                this.disabled = false;
            }
        });
    }


    // =========================================================
    // ELIMINAR MEDIA DE ARTISTAS
    // eL ADMIN PEUDE ELIMINAR FOTOS, VIDEOS, AUDIOS QUE CONSIDERE QUE NO ESTAN PERMITIDOS
    // =========================================================

    document.addEventListener('click', async function (e) {

        const btn = e.target.closest('.btn-delete-media');
        if (!btn) return;

        // debe confirmar anets de eliminar
        const confirmado = await confirmAlert(
            'Eliminar elemento',
            'Esta acción no se puede deshacer.'
        );

        if (!confirmado) {
            return;
        }

        // Guarda referencias ANTES de cerrar el modal para poder quitarlo de la vista
        // porque sino se pierde al eliminarlo.
        const url = btn.dataset.url;
        const itemEl = btn.closest('.media-item');

        try {

            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                await errorAlert(
                    'No se pudo eliminar',
                    data.message ?? 'Intentá nuevamente.'
                );
                return;
            }

            itemEl.style.transition = 'opacity 0.25s';
            itemEl.style.opacity = '0';

            setTimeout(() => {
                itemEl.remove();
                actualizarBadges();
            }, 250);

            showToast(
                'success',
                'Elemento eliminado correctamente.'
            );

        } catch (e) {

            await errorAlert(
                'Error de conexión',
                'No fue posible comunicarse con el servidor.'
            );

        }

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

});
