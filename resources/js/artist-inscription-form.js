document.addEventListener('DOMContentLoaded', function () {
   // Cargar géneros según disciplina
    const disciplina = document.getElementById('disciplina_id');

    if ( disciplina) {

        disciplina.addEventListener('change', function () {
        const disciplinaId = this.value;
        const container = document.getElementById('generos-container');
        const lista = document.getElementById('generos-lista');

        if (!disciplinaId) {
            container.style.display = 'none';
            lista.innerHTML = '';
            return;
        }

        fetch(`/api/generos/${disciplinaId}`)
            .then(r => r.json())
            .then(generos => {
                lista.innerHTML = '';
                if (generos.length === 0) {
                    container.style.display = 'none';
                    return;
                }
                generos.forEach(g => {
                    lista.innerHTML += `
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox"
                                name="generos[]" value="${g.id}" id="genero_${g.id}">
                            <label class="form-check-label" for="genero_${g.id}">
                                ${g.nombre}
                            </label>
                        </div>`;
                });
                container.style.display = 'block';
            });
    });
    }

    // Mostrar/ocultar detalle formación
    document.getElementById('tiene_formacion').addEventListener('change', function () {
        const container = document.getElementById('detalle-formacion-container');
        container.style.display = this.value === '1' ? 'block' : 'none';
    });


    // Preview imagen de perfil
    const imgPerfilInput = document.getElementById('img_perfil');
    if (imgPerfilInput) {
        imgPerfilInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('img-preview').src = e.target.result;
                    document.getElementById('preview-container').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

});
