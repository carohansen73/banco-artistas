document.addEventListener('DOMContentLoaded', () => {
    const track    = document.querySelector('.eventos-slider-track');
    if (!track) return;

    const wrapper  = document.querySelector('.eventos-slider-wrapper');
    const btnPrev  = document.querySelector('.eventos-btn-prev');
    const btnNext  = document.querySelector('.eventos-btn-next');
    const cards    = Array.from(track.children);

    let current = 0;
    const visibleDesktop = 3;

    function cardWidth() {
        return cards[0].getBoundingClientRect().width + 12; // 12 = gap
    }

    function isMobile() {
        return window.innerWidth <= 768;
    }

    function visibleCount() {
        return isMobile() ? 1 : visibleDesktop;
    }

    function maxIndex() {
        return Math.max(0, cards.length - visibleCount());
    }

    function goTo(index) {
        current = Math.max(0, Math.min(index, maxIndex()));
        track.style.transform = `translateX(-${current * cardWidth()}px)`;
        if (btnPrev) btnPrev.disabled = current === 0;
        if (btnNext) btnNext.disabled = current >= maxIndex();
    }

    // botones
    btnPrev?.addEventListener('click', () => goTo(current - 1));
    btnNext?.addEventListener('click', () => goTo(current + 1));

    // swipe touch
    let startX = 0;
    let isDragging = false;

    track.addEventListener('touchstart', e => {
        startX = e.touches[0].clientX;
    }, { passive: true });

    track.addEventListener('touchend', e => {
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) goTo(current + (diff > 0 ? 1 : -1));
    }, { passive: true });

    // drag mouse (opcional, para desktop también)
    track.addEventListener('mousedown', e => {
        startX = e.clientX;
        isDragging = true;
        track.classList.add('is-dragging');
    });
    document.addEventListener('mouseup', e => {
        if (!isDragging) return;
        isDragging = false;
        track.classList.remove('is-dragging');
        const diff = startX - e.clientX;
        if (Math.abs(diff) > 40) goTo(current + (diff > 0 ? 1 : -1));
    });

    // init
    goTo(0);

    // recalcular al cambiar tamaño de ventana
    window.addEventListener('resize', () => goTo(Math.min(current, maxIndex())));
});
