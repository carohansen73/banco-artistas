// ─── CARRUSEL DE TAGS ───────────────────────────────────────────
"use strict";

document.addEventListener('DOMContentLoaded', () => {

    const tagsScroll    = document.getElementById('tags-disciplina');
    const arrowLeft     = document.getElementById('tags-arrow-left');
    const arrowRight    = document.getElementById('tags-arrow-right');
    const SCROLL_AMOUNT = 160;

    function actualizarFlechas() {
        arrowLeft.disabled  = tagsScroll.scrollLeft <= 0;
        arrowRight.disabled = tagsScroll.scrollLeft + tagsScroll.clientWidth >= tagsScroll.scrollWidth - 1;
    }

    arrowLeft.addEventListener('click', () => {
        tagsScroll.scrollBy({ left: -SCROLL_AMOUNT, behavior: 'smooth' });
    });
    arrowRight.addEventListener('click', () => {
        tagsScroll.scrollBy({ left: SCROLL_AMOUNT, behavior: 'smooth' });
    });

    tagsScroll.addEventListener('scroll', actualizarFlechas);
    window.addEventListener('resize', actualizarFlechas);

    // estado inicial
    actualizarFlechas();
});
