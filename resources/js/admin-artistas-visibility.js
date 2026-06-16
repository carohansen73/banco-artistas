document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.querySelectorAll('[data-visibility-toggle]').forEach((toggle) => {
        toggle.addEventListener('change', async (event) => {
            const input = event.currentTarget;
            const url = input.dataset.url;
            const previousChecked = !input.checked;
            const row = input.closest('[data-artista-row]');
            const label = row?.querySelector('[data-visibility-label]');

            if (!url || !csrfToken) {
                input.checked = previousChecked;
                return;
            }

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
                    body: JSON.stringify({ visible: input.checked }),
                });

                if (!response.ok) {
                    throw new Error('No se pudo actualizar la visibilidad.');
                }

                const data = await response.json();
                input.checked = Boolean(data.visible);

                if (label) {
                    label.textContent = data.visible ? 'Visible' : 'Oculto';
                }
            } catch {
                input.checked = previousChecked;
                window.alert('No se pudo actualizar la visibilidad. Intentá de nuevo.');
            } finally {
                input.disabled = false;
            }
        });
    });
});
