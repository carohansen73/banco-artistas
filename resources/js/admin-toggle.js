/**
 * Gestión de toggles (on/off)
 * Maneja 3 toggles distintos:
 * - Visibilidad de artistas
 * - Visibilidad de eventos
 * - Destacado de eventos
 */
document.addEventListener('DOMContentLoaded', () => {
    // Obtener el token CSRF que Laravel pone en el <meta< del HTML. Necesario para q Laravel acepte peticiones PATCH.
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    /**
     * makeToggleHandler es una función reutilizable que configura un tipo de toggle.
     * Recibe un objeto con 4 parámetros:
     * - selectorToggle : Atributo HTML q identifica lso checkboxes de cada tipo (ej: [data-visibility-toggle]).
     * - selectorLabel: el <span> q muestra texto debajo del switch ([data-visibility-label]).
     * - campo: el cmapo q se envía al servidor para el update (visible o destacado).
     * - labels: etiquetas q s muestran según el estado (on: visible, off: oculto).
     */
    function makeToggleHandler({ toggleSelector, labelSelector, campo, labels }) {
        document.querySelectorAll(toggleSelector).forEach((toggle) => {
            toggle.addEventListener('change', async (event) => {
                const input = event.currentTarget; // El checkbox q el usuario acaba de clickear
                const url = input.dataset.url; // URL a la q hay q hacer patch
                const previousChecked = !input.checked; // Estado anterior (como change ya cambió el valor, el anterior es el opuesto al actual)
                const row = input.closest('[data-toggle-row]');
                const label = row?.querySelector(labelSelector);

                // Chequea url y token
                if (!url || !csrfToken) {
                    input.checked = previousChecked;
                    return;
                }

                // Deshabilita el checkbox mientras dura la petición.
                input.disabled = true;

                try {
                    const response = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ [campo]: input.checked }),
                    });

                    if (!response.ok) {
                        throw new Error('No se pudo actualizar.');
                    }

                    // Parsea el JSON de la respuesta
                    const data = await response.json();
                    // Sincroniza el checkbox con el valor confirmado x el servidor
                    input.checked = Boolean(data[campo]);

                    // Acualiza el texto debajo del toggle
                    if (label) {
                        label.textContent = data[campo] ? labels.on : labels.off;
                    }
                } catch {
                    input.checked = previousChecked;
                    window.alert('No se pudo actualizar. Intentá de nuevo.');
                } finally {
                    // Vuelve a habilitar el checkbox
                    input.disabled = false;
                }
            });
        });
    }

    /**
     * Toggle de visibilidad (artistas)
     */
    makeToggleHandler({
        toggleSelector: '[data-visibility-toggle]',
        labelSelector: '[data-visibility-label]',
        campo: 'visible',
        labels: { on: 'Visible', off: 'Oculto' },
    });

    /**
     * Toggle activo (eventos)
     */
    makeToggleHandler({
        toggleSelector: '[data-eventos-activo-toggle]',
        labelSelector: '[data-eventos-activo-label]',
        campo: 'activo',
        labels: { on: 'Activo', off: 'Inactivo' },
    });
    /**
     * Toggle de destacado (eventos)
     */
    makeToggleHandler({
        toggleSelector: '[data-destacado-toggle]',
        labelSelector: '[data-destacado-label]',
        campo: 'destacado',
        labels: { on: 'Destacado', off: 'Normal' },
    });

});
