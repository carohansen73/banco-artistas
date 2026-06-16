document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('admin-sidebar-overlay');

    if (!sidebar) {
        return;
    }

    const toggles = document.querySelectorAll('[data-admin-sidebar-toggle]');
    const mobileMedia = window.matchMedia('(max-width: 1023px)');

    const isMobile = () => mobileMedia.matches;

    const setOpen = (open) => {
        sidebar.classList.toggle('translate-x-0', open);
        sidebar.classList.toggle('-translate-x-full', !open);

        sidebar.classList.toggle('is-open', open);
        sidebar.dataset.open = open ? 'true' : 'false';

        overlay.classList.toggle('hidden', !open);

        toggles.forEach((button) => {
            button.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    };

    toggles.forEach((button) => {
        button.addEventListener('click', () => {
            if (!isMobile()) {
                return;
            }

            setOpen(!sidebar.classList.contains('is-open'));
        });
    });

    sidebar.querySelectorAll('[data-admin-sidebar-link]').forEach((link) => {
        link.addEventListener('click', () => {
            if (isMobile()) {
                setOpen(false);
            }
        });
    });

    // Cerrar sidebar haciendo tocando afuera de la msima
    overlay?.addEventListener('click', () => {
        setOpen(false);
    });

    mobileMedia.addEventListener('change', (event) => {
        if (!event.matches) {
            setOpen(false);
        }
    });
});
