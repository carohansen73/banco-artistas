import { confirmAlert } from './utils/notifications';

document.addEventListener('DOMContentLoaded', () => {

    /**
     * Eliminar disciplina
     */
    document.querySelectorAll('.delete-disciplina-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const nombre = this.dataset.disciplina;

            const confirmado = await confirmAlert(
                'Eliminar disciplina',
                `¿Estás seguro que querés eliminar la disciplina "${nombre}"?`
            );

            if (confirmado) {
                this.submit();
            }

        });

    });


    /**
     * Eliminar género
     */
    document.querySelectorAll('.delete-genero-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const nombre = this.dataset.genero;

            const confirmado = await confirmAlert(
                'Eliminar género',
                `¿Estás seguro que querés eliminar el género "${nombre}"?`
            );

            if (confirmado) {
                this.submit();
            }

        });

    });
});
