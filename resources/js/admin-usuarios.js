document.addEventListener('DOMContentLoaded', () => {
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
                    alert(data.error ?? 'Error al actualizar.');
                    this.checked = !prev; // revertir
                    return;
                }

                const data = await res.json();
                if (label) {
                    label.textContent = data.is_active ? 'Activo' : 'Bloqueado';
                }

            } catch (e) {
                alert('Error de red.');
                this.checked = !prev;
            }
        });
    });
});
