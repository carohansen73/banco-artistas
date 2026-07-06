import {
    confirmAlert,
    successAlert,
    errorAlert
} from './utils/notifications';
import { showToast } from './utils/flash-toast';

document.addEventListener('DOMContentLoaded', () => {
    /**
     * Activar / desactivar usuarios
     *
     * **/
    document.querySelectorAll('.user-active-toggle').forEach(toggle => {
        toggle.addEventListener('change', async function () {
            const url   = this.dataset.url;
            const label = this.closest('td').querySelector('.user-active-label');
            const prev  = this.checked;

            // Optimistic UI: ya cambió visualmente con el check, esperamos respuesta
            try {
                const res = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':       'application/json',
                        'Content-Type': 'application/json',
                    },
                });

                if (!res.ok) {
                    const data = await res.json();
                    await errorAlert(
                        'No se pudo actualizar',
                        data.error ?? 'Error al actualizar.'
                    )
                    this.checked = !prev; // revertir
                    return;
                }

                const data = await res.json();
                if (label) {
                    label.textContent = data.is_active ? 'Activo' : 'Bloqueado';

                    showToast(
                        'success',
                        data.is_active
                            ? 'Usuario activado correctamente.'
                            : 'Usuario bloqueado correctamente.'
                    );
                }

            } catch (e) {
                  await errorAlert(
                        'Error de conexión',
                        data.error ?? 'No fue posible comunicarse con el servidor.'
                    )
                this.checked = !prev;
            }
        });
    });

    /**
     * Cambiar rol a usuarios
     */

    document.querySelectorAll('.user-role-select').forEach(select => {
        let prevValue = select.value;

        select.addEventListener('change', async function () {

            // Pedir confirmación
            const nuevoRol = this.value;
            const nombre   = this.dataset.userName;
            const label    = nuevoRol.replace('-', ' ');

            const confirmado = await confirmAlert(
                'Cambiar rol',
                `¿Estás seguro que querés cambiar el rol de ${nombre} a ${label}?`
            );

            // Si no confirma corto
            if (!confirmado) {
                this.value = prevValue;
                return;
            }

            const url = this.dataset.url;

            try {
                const res = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':       'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ role: nuevoRol }),
                });

                const data = await res.json();

                if (!res.ok) {
                    await errorAlert(
                        'No se pudo actualizar',
                        data.error ?? 'Error al actualizar el rol.'
                    );
                    this.value = prevValue;
                    return;
                }

                prevValue = nuevoRol;
                await successAlert(
                    'Rol actualizado',
                    `El rol de ${nombre} fue actualizado a ${data.label}.`
                );
            } catch (e) {
                console.error('Error real capturado:', e);
                this.value = prevValue;
                await errorAlert(
                    'Error de conexión',
                    'No fue posible comunicarse con el servidor.'
                );
            }
        });
    });

});
