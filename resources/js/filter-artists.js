"use strict";

document.addEventListener('DOMContentLoaded', () => {

    const inputNombre     = document.getElementById('filter-nombre');
    const selectDisciplina = document.getElementById('filter-disciplina');
    const selectGenero    = document.getElementById('filter-genero');
    const btnLimpiar      = document.getElementById('btn-limpiar');
    const contador        = document.getElementById('contador-resultados');
    const tagsDisciplina   = document.querySelectorAll('.tag-disc'); // Tag disciplina
    const btnVerMas        = document.getElementById('btn-ver-mas'); // ver más
    const verMasWrap       = document.getElementById('ver-mas-wrap'); // ver más
    const verMasRestantes  = document.getElementById('ver-mas-restantes'); // ver más

    const POR_PAGINA = 9;       // cuántos mostrar de entrada y en cada "ver más"


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
                .then(data => {
                    todosLosArtistas = data;
                    mostradosHasta   = 0;
                    // limpia el container antes de renderizar la primera tanda
                    const container  = document.getElementById('container-artists');
                    container.innerHTML = '';

                    if (data.length === 0) {
                        container.innerHTML = `
                            <div class="col-12 text-center text-muted py-5">
                                <p>No se encontraron artistas con esos filtros.</p>
                            </div>`;
                        verMasWrap.style.setProperty('display', 'none', 'important');
                        actualizarContador();
                        return;
                    }

                    mostrarSiguientes();
                    actualizarContador();
                }
                    // renderCards(data) - Ahora corto resultados antes de renderizar
                )
                .catch(console.error);
        }, 300); // espera 300ms después del último cambio
    }

    // ─── PAGINACIÓN FRONTEND ────────────────────────────────────────────────

    function mostrarSiguientes() {
        const siguiente = todosLosArtistas.slice(mostradosHasta, mostradosHasta + POR_PAGINA);
        siguiente.forEach(artista => appendCard(artista));
        mostradosHasta += siguiente.length;
        actualizarBotonVerMas();
    }

    function actualizarBotonVerMas() {
        const restantes = todosLosArtistas.length - mostradosHasta;
        if (restantes > 0) {
            verMasWrap.style.setProperty('display', 'block', 'important');
            verMasRestantes.textContent = `(${restantes} más)`;
        } else {
            verMasWrap.style.setProperty('display', 'none', 'important');
        }
    }

    function actualizarContador() {
        if (!contador) return;
        const total = todosLosArtistas.length;
        contador.innerHTML = total === 0
            ? `<span class="contador-texto">Sin resultados</span>`
            : `<span class="contador-numero">${total}</span>
               <span class="contador-texto">
                    artista${total !== 1 ? 's' : ''}
                </span>`;
    }


    // ─── RENDER DE UNA CARD ─────────────────────────────────────────────────
    // Escapa HTML para evitar que datos cargados por artistas (nombre, localidad,
    // disciplina, géneros) se interpreten como markup/script al insertarlos con innerHTML.
    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
        }[char]));
    }

    function appendCard(a) {
        const container = document.getElementById('container-artists');

        const nombreArtistico = escapeHtml(a.nombre_artistico);
        const localidad       = escapeHtml(a.localidad);
        const disciplina      = escapeHtml(a.disciplina);
        const slug            = encodeURIComponent(a.slug);
        const imgPerfil       = encodeURI(a.img_perfil || '');

        // Renderizar géneros en la card
        const generosBadges = (a.generos || [])
            .map(g => `<span class="artista-badge genero">${escapeHtml(g)}</span>`)
            .join('');

        // Sacar tildes
        const discSlug = (a.disciplina || '')
            .toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/\s+/g, '-');

        const imgOrAvatar = (a.img_perfil && !a.img_perfil.includes('default'))
            ? `<img src="${imgPerfil}" alt="${nombreArtistico}" loading="lazy">`
            : `<div class="artista-avatar-default">${escapeHtml(a.nombre_artistico.charAt(0).toUpperCase())}</div>`;


        // Crear la card del artista
        const col = document.createElement('div');
        col.className = 'col-lg-4 col-md-6 col-sm-12';
        col.innerHTML = `
            <div class="artista-card" onclick="window.location='/artistas/${slug}'">
                <div class="artista-card-img">
                    ${imgOrAvatar}
                    <div class="artista-card-overlay">
                        <a href="/artistas/${slug}" class="btn btn-red btn-sm rounded-pill">Ver perfil</a>
                    </div>
                </div>
                <div class="artista-card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="artista-card-nombre">${nombreArtistico}</h4>
                        ${a.disciplina
                            ? `<span class="artista-card-disciplina disc-${discSlug}">${disciplina}</span>`
                            : ''}
                    </div>
                    <div class="artista-card-meta">
                        ${a.localidad ? `<span class="card-localidad"><i class="fas fa-map-marker-alt me-1"></i> ${localidad}</span>` : ''}
                    </div>
                    ${generosBadges ? `<div class="artista-card-generos">${generosBadges}</div>` : ''}
                </div>
            </div>`;
        container.appendChild(col);
    }

    // ─── DISCIPLINAS ────────────────────────────────────────────────

    // Sincroniza tags con select
    function setDisciplina(id) {
        // actualiza el select
        selectDisciplina.value = id;

        // actualiza el tag activo
        tagsDisciplina.forEach(t => {
            t.classList.toggle('active', t.dataset.id === id);
        });

        // actualiza géneros y filtra
        actualizarGeneros(id);
        filtrar();
    }


    /**
     * Filtrar géneros por disciplina
     * @param {*} disciplinaId
     * @returns
     */
    async function actualizarGeneros(disciplinaId) {
        // Resetear el select de géneros
        selectGenero.innerHTML = '<option value="">Todos los géneros</option>';

        // Si no hay disciplina, dejarlo con "Todos"
        if (!disciplinaId) return;

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


    // ------------------------------------------------------------------------------------
    // ------------------------------------- LISTENERS ------------------------------------
    // ------------------------------------------------------------------------------------

    inputNombre.addEventListener('input', filtrar);
    selectGenero.addEventListener('change', filtrar);

    // Select Disciplina filtra resultadoe y tmb filtra los generos q le pertenecen a la disciplina seleccionada
    selectDisciplina.addEventListener('change', () => { setDisciplina(selectDisciplina.value); });

    // Tags de disciplinas
    tagsDisciplina.forEach(tag => {
        tag.addEventListener('click', () => setDisciplina(tag.dataset.id));
    });

    btnLimpiar.addEventListener('click', () => {
        inputNombre.value       = '';
        selectDisciplina.value  = '';
        selectGenero.value      = '';
        actualizarGeneros(''); // Restaura "Todos los géneros"
        filtrar();
    });

    btnVerMas.addEventListener('click', mostrarSiguientes);

    // ─── CARGA INICIAL ──────────────────────────────────────────────────────

    // La carga inicial ya nos e hace desde el servidor, sino desde acá
    // filtrar();
        let todosLosArtistas = window.artistasIniciales || [];  // cache del resultado actual
        let mostradosHasta   = document.querySelectorAll('#container-artists > div').length;   // índice hasta donde muestra
        actualizarBotonVerMas();
        actualizarContador();
});
