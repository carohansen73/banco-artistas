import Swal from 'sweetalert2';

export const confirmAlert = async (title, text) => {
    const result = await Swal.fire({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
    });

    return result.isConfirmed;
};

export const successAlert = (title, text) => {
    return Swal.fire({
        icon: 'success',
        title,
        text,
        timer: 1800,
        showConfirmButton: false,
    });
};

export const errorAlert = (title, text) => {
    return Swal.fire({
        icon: 'error',
        title,
        text,
    });
};
