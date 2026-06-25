document.addEventListener('DOMContentLoaded', function () {
    const lista  = document.getElementById('integrantes-lista');
    const btnAdd = document.getElementById('btn-add-integrante');

    if (!lista || !btnAdd) return;

    function crearFila(valor = '') {
        const div = document.createElement('div');
        div.className = 'd-flex align-items-center gap-2 mb-2 integrante-row';
        div.innerHTML = `
            <input type="text" name="integrantes[]"
                class="form-control"
                placeholder="Nombre del integrante"
                value="${valor}">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-integrante">&times;</button>
        `;
        return div;
    }

    // Agregar fila nueva
    btnAdd.addEventListener('click', function () {
        lista.appendChild(crearFila());
    });

    // Eliminar fila (delegación de eventos)
    lista.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-integrante')) {
            e.target.closest('.integrante-row').remove();
        }
    });
});
