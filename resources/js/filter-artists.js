"use strict";

document.addEventListener('DOMContentLoaded', () => {

    const inputNombre     = document.getElementById('filter-nombre');
    const selectDisciplina = document.getElementById('filter-disciplina');
    const selectGenero    = document.getElementById('filter-genero');
    const btnLimpiar      = document.getElementById('btn-limpiar');
    const contador        = document.getElementById('contador-resultados');

    let debounceTimer;

    function filtrar() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const params = new URLSearchParams({
                busqueda:   inputNombre.value,
                disciplina: selectDisciplina.value,
                genero:     selectGenero.value,
            });

            fetch(`/buscador-de-artistas?${params}`)
                .then(r => r.json())
                .then(data => renderCards(data))
                .catch(console.error);
        }, 300); // espera 300ms después del último cambio
    }

    function renderCards(artistas) {
        const container = document.getElementById('container-artists');
        container.innerHTML = '';

        contador.textContent = artistas.length === 0
            ? 'Sin resultados'
            : `${artistas.length} artista${artistas.length !== 1 ? 's' : ''}`;

        if (artistas.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center text-muted py-5">
                    <p>No se encontraron artistas con esos filtros.</p>
                </div>`;
            return;
        }

        artistas.forEach(a => {
            const generosBadges = (a.generos || [])
                .map(g => `<span class="artista-badge genero">${g}</span>`)
                .join('');

            container.innerHTML += `
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="artista-card" onclick="window.location='/artistas/${a.slug}'">
                        <div class="artista-card-img">
                            <img src="${a.img_perfil}" alt="${a.nombre_artistico}">
                            <div class="artista-card-overlay">
                                <a href="/artistas/${a.slug}" class="btn btn-red btn-sm rounded-pill">Ver perfil</a>
                            </div>
                        </div>
                        <div class="artista-card-body">
                            <h4 class="artista-card-nombre">${a.nombre_artistico}</h4>
                            <div class="artista-card-meta">
                                ${a.disciplina ? `<span class="artista-badge disciplina">${a.disciplina}</span>` : ''}
                                ${a.localidad  ? `<span class="artista-badge localidad"><i class="bi bi-geo-alt-fill"></i> ${a.localidad}</span>` : ''}
                            </div>
                            ${generosBadges ? `<div class="artista-card-generos">${generosBadges}</div>` : ''}
                        </div>
                    </div>
                </div>`;
        });
    }

    inputNombre.addEventListener('input', filtrar);
    selectDisciplina.addEventListener('change', filtrar);
    selectGenero.addEventListener('change', filtrar);

    btnLimpiar.addEventListener('click', () => {
        inputNombre.value       = '';
        selectDisciplina.value  = '';
        selectGenero.value      = '';
        filtrar();
    });
});
