document.addEventListener('DOMContentLoaded', () => {

    document.getElementById('imagen_portada').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();

        reader.onload = e => {
            document.getElementById('img-preview').src = e.target.result;
            document.getElementById('preview-container').style.display = 'block';
        }
        reader.readAsDataURL(file);
    });

});
