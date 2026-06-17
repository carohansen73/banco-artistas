document.addEventListener('DOMContentLoaded', () => {
    console.log('entro!');
    const toasts = document.querySelectorAll('[data-toast]');

    toasts.forEach(toast => {
        // Entrada: pequeña animación al aparecer
        requestAnimationFrame(() => {
            toast.classList.remove('opacity-0', 'translate-y-2');
        });

        // Auto-cierre a los 4 segundos
        const timer = setTimeout(() => cerrar(toast), 4000);

        // Botón cerrar manual
        toast.querySelector('[data-toast-close]')?.addEventListener('click', () => {
            clearTimeout(timer);
            cerrar(toast);
        });
    });

    function cerrar(toast) {
        toast.classList.add('opacity-0', 'translate-y-2');
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }
});
