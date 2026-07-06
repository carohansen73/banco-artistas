import Swal from 'sweetalert2';

/**
 * Configuración base para todos los modales
 */
const swal = Swal.mixin({
    background: '#1f2937',
    color: '#f3f4f6',

    confirmButtonColor: '#4f46e5',
    cancelButtonColor: '#6b7280',

    reverseButtons: true,

    customClass: {
        popup: 'rounded-xl',
        title: 'text-lg font-semibold',
        confirmButton: 'px-4 py-2',
        cancelButton: 'px-4 py-2',
    }
});



export const confirmAlert = async (title, text) => {
    const result = await swal.fire({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar',
    });

    return result.isConfirmed;
};

export const successAlert = (title, text) => {
    return swal.fire({
        icon: 'success',
        title,
        text,
        timer: 1800,
        showConfirmButton: false,
    });
};

export const errorAlert = (title, text) => {
    return swal.fire({
        icon: 'error',
        title,
        text,
    });
};
