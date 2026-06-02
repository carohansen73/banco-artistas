document.addEventListener('DOMContentLoaded', () => {
    const items     = Array.from(document.querySelectorAll('.galeria-item'));
    const lightbox  = document.getElementById('lightbox');
    const img       = document.getElementById('lightbox-img');
    const titulo    = document.getElementById('lightbox-titulo');
    let current     = 0;

    function abrir(index) {
        current = index;
        img.src        = items[index].dataset.src;
        titulo.textContent = items[index].dataset.titulo || '';
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function cerrar() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    function navegar(dir) {
        current = (current + dir + items.length) % items.length;
        img.src        = items[current].dataset.src;
        titulo.textContent = items[current].dataset.titulo || '';
    }

    items.forEach((item, i) => item.addEventListener('click', () => abrir(i)));

    document.getElementById('lightbox-cerrar').addEventListener('click', cerrar);
    document.getElementById('lightbox-prev').addEventListener('click', () => navegar(-1));
    document.getElementById('lightbox-next').addEventListener('click', () => navegar(1));

    // Cerrar clickeando el fondo
    lightbox.addEventListener('click', e => { if (e.target === lightbox) cerrar(); });

    // Navegación con teclado
    document.addEventListener('keydown', e => {
        if (!lightbox.classList.contains('active')) return;
        if (e.key === 'ArrowLeft')  navegar(-1);
        if (e.key === 'ArrowRight') navegar(1);
        if (e.key === 'Escape')     cerrar();
    });
});
