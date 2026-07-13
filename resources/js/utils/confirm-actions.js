// import Swal from 'sweetalert2';
import { confirmAlert } from './notifications';

/**
 * Inicializa las confirmaciones mediante SweetAlert2.
 *
 * Intercepta todos los formularios que tengan la clase
 * `.confirm-action` y muestra un diálogo de confirmación
 * antes de enviarlos.
 *
 * Personalización mediante data attributes:
 * - data-title
 * - data-text
 * - data-confirm
 * - data-cancel (opcional)
 */
export function initConfirmActions() {
     console.log('initConfirmActions');
    document.querySelectorAll('.confirm-action').forEach(form => {

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

           const confirmed = await confirmAlert(
                form.dataset.title || '¿Estás seguro?',
                form.dataset.text || 'Esta acción no se puede deshacer.',
                form.dataset.confirm || 'Aceptar',
                form.dataset.cancel || 'Cancelar'
            );

            if (confirmed) {
                form.submit();
            }

        });

    });
}

