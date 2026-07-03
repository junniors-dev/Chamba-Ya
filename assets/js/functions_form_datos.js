const fotoPerfil = document.getElementById('fotoPerfil');

fotoPerfil.addEventListener('change', (e) => {
    const archivo = e.target.files[0];
    if (!archivo) return;

    // Validación: solo JPG/PNG y máximo 2 MB
    const tiposPermitidos = ['image/jpeg', 'image/png'];
    const tamanoMax = 2 * 1024 * 1024; // 2 MB

    if (!tiposPermitidos.includes(archivo.type)) {
        alert('Formato no válido. Solo se permiten imágenes JPG o PNG.');
        e.target.value = '';
        return;
    }
    if (archivo.size > tamanoMax) {
        alert('La imagen es muy pesada. El tamaño máximo es 2 MB.');
        e.target.value = '';
        return;
    }

    const fotoPerfilPreview = document.getElementById('fotoPerfilPreview');
    const contenedorFotoPerfil = document.querySelector('.profilePictureImage');

    const reader = new FileReader();

    reader.onload = () => {
        fotoPerfilPreview.src = reader.result;
        fotoPerfilPreview.style.visibility = 'visible';
        contenedorFotoPerfil.style.display = 'flex';
    };

    reader.readAsDataURL(archivo);
});

//APARTADO DE FUNCIONES DE LOS SELECTS DE DEPARTAMENTOS - PROVINCIAS - DISTRITOS

// Construye el HTML de <option> de una sola vez (evita innerHTML += en cada vuelta)
function construirOpciones(items, valueKey, textKey, placeholder) {
    let html = `<option value="">${placeholder}</option>`;
    items.forEach(item => {
        html += `<option value="${item[valueKey]}">${item[textKey]}</option>`;
    });
    return html;
}

//CARGAR PROVINCIAS
document.getElementById('departamento').addEventListener('change', function() {
    let idDepartamento = this.value;

    fetch(basePath + 'core/db/getProvincias.php?id=' + idDepartamento)
        .then(res => res.json())
        .then(data => {
            document.getElementById('provincia').innerHTML =
                construirOpciones(data, 'idProvincia', 'nombre', 'Seleccione una provincia');
            document.getElementById('distrito').innerHTML =
                '<option value="">Seleccione un distrito</option>';
        })
        .catch(() => {
            alert('No se pudieron cargar las provincias. Revisa tu conexión e inténtalo de nuevo.');
        });
});

//CARGAR DISTRITOS
document.getElementById('provincia').addEventListener('change', function() {
    let idProvincia = this.value;

    fetch(basePath + 'core/db/getDistritos.php?id=' + idProvincia)
        .then(res => res.json())
        .then(data => {
            document.getElementById('distrito').innerHTML =
                construirOpciones(data, 'idDistrito', 'nombre', 'Seleccione un distrito');
        })
        .catch(() => {
            alert('No se pudieron cargar los distritos. Revisa tu conexión e inténtalo de nuevo.');
        });
});
