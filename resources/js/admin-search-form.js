/**
 * Buscador AJAX reutilizable para listados del backoffice.
 *
 * Funcionalidades:
 * - Búsqueda en tiempo real (debounce)
 * - Paginación AJAX
 * - Mantiene el filtro al cambiar de página
 * - Actualiza el contador de registros
 * - Reinicia a la página 1 al modificar la búsqueda
 */

document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('[data-search-form]');

    if (!form) return;

    const input = form.querySelector('input[type="search"]');

    if (!input) return;

    let timeout = null;

    /**
     * Búsqueda en tiempo real.
     * Espera 300ms desde la última tecla para evitar demasiadas peticiones.
     */
    input.addEventListener('input', () => {

        clearTimeout(timeout);

        timeout = setTimeout(() => {

            buscar(construirUrl(form));

        }, 300);

    });

    // Activa la paginación AJAX al cargar la página.
    registrarEventosPaginacion(form);

});


/**
 * Construye la URL de búsqueda.
 *
 * Siempre elimina el parámetro "page" para volver
 * a la primera página cuando cambia el texto buscado.
 *
 * @param {HTMLFormElement} form
 * @param {Number|null} page
 * @returns {URL}
 */
function construirUrl(form, page = null) {

    const url = new URL(form.action);

    const formData = new FormData(form);

    for (const [key, value] of formData.entries()) {

        if (value !== '') {
            url.searchParams.set(key, value);
        }

    }

    if (page) {
        url.searchParams.set('page', page);
    }

    return url;

}


/**
 * Realiza la petición AJAX.
 *
 * @param {URL|string} url
 */
async function buscar(url) {

    try {

        const response = await fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Error al obtener resultados.');
        }

        const data = await response.json();

        actualizarVista(data);

    } catch (error) {

        console.error(error);

    }

}


/**
 * Actualiza la tabla, contador y paginación.
 *
 * @param {Object} data
 */
function actualizarVista(data) {

    document.querySelector('#tabla-resultados').innerHTML = data.table;

    document.querySelector('#paginacion-resultados').innerHTML = data.pagination;

    document.querySelector('#total-registros').textContent =
        `${data.total} registro(s)`;

    // Como la paginación se reemplazó, hay que volver a registrar los eventos.
    registrarEventosPaginacion(document.querySelector('[data-search-form]'));

}


/**
 * Convierte la paginación de Laravel en AJAX.
 *
 * @param {HTMLFormElement} form
 */
function registrarEventosPaginacion(form) {

    document
        .querySelectorAll('#paginacion-resultados a')
        .forEach(link => {

            link.onclick = async (e) => {

                e.preventDefault();

                const url = new URL(link.href);

                const page = url.searchParams.get('page');

                await buscar(construirUrl(form, page));

            };

        });

}
