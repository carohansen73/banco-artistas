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

        if (contador) {
            contador.textContent = artistas.length === 0
                ? 'Sin resultados'
                : `${artistas.length} artista${artistas.length !== 1 ? 's' : ''}`;
        }

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

                const discSlug = (a.disciplina || '')
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // saca tildes
                .replace(/\s+/g, '-');

            container.innerHTML += `
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="artista-card" onclick="window.location='/artistas/${a.slug}'">
                        <div class="artista-card-img">
                            <img src="${a.img_perfil}" alt="${a.nombre_artistico}" loading="lazy">
                            <div class="artista-card-overlay">
                                <a href="/artistas/${a.slug}" class="btn btn-red btn-sm rounded-pill">Ver perfil</a>
                            </div>
                        </div>

                        <div class="artista-card-body">
                            <div class="d-flex justify-content-between">
                                <h4 class="artista-card-nombre">${a.nombre_artistico}</h4>
                                ${a.disciplina
                                    ? `<span class="artista-card-disciplina disc-${discSlug}">${a.disciplina}</span>`
                                    : ''}
                            </div>
                            <div class="artista-card-meta">
                                ${a.localidad  ? `<span class="card-localidad"><i class="fas fa-map-marker-alt me-1"></i> ${a.localidad}</span>` : ''}
                            </div>
                            ${generosBadges ? `<div class="artista-card-generos">${generosBadges}</div>` : ''}
                        </div>
                    </div>
                </div>`;
        });
    }

    /**
     * Filtrar géneros por disciplina
     * @param {*} disciplinaId
     * @returns
     */
    async function actualizarGeneros(disciplinaId) {
        // Resetear el select de géneros
        selectGenero.innerHTML = '<option value="">Todos los géneros</option>';

        if (!disciplinaId) return; // Si no hay disciplina, dejarlo con "Todos"

        try {
            const res  = await fetch(`/api/generos/${disciplinaId}`);
            const data = await res.json();

            data.forEach(g => {
                const opt = document.createElement('option');
                opt.value       = g.id;
                opt.textContent = g.nombre;
                selectGenero.appendChild(opt);
            });
        } catch (e) {
            console.error('Error al cargar géneros:', e);
        }
    }

    inputNombre.addEventListener('input', filtrar);
    // Disciplina filtra resultadoe y tmb filtra los generos q le pertenecen a la disciplina seleccionada
    selectDisciplina.addEventListener('change', () => {
        filtrar();
        actualizarGeneros(selectDisciplina.value);
    });
    selectGenero.addEventListener('change', filtrar);

    btnLimpiar.addEventListener('click', () => {
        inputNombre.value       = '';
        selectDisciplina.value  = '';
        selectGenero.value      = '';
        actualizarGeneros(''); // Restaura "Todos los géneros"
        filtrar();
    });
});
