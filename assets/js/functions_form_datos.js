const  fotoPerfil = document.getElementById('fotoPerfil');

fotoPerfil.addEventListener('change', (e) => {
    console.log(e.target.files[0]);

    const fotoPerfilPreview = document.getElementById('fotoPerfilPreview');
    const contenedorFotoPerfil = document.querySelector('.profilePictureImage');

    const reader = new FileReader();

    reader.onload = () => {
        fotoPerfilPreview.src = reader.result;
        fotoPerfilPreview.style.visibility = 'visible';
        contenedorFotoPerfil.style.display = 'flex';
    };

    reader.readAsDataURL(e.target.files[0]);
});

//APARTADO DE FUNCIONES DE LOS SELECTS DE DEPARTAMENTOS - PROVINCIAS - DISTRITOS

//CARGAR PROVINCIAS

document.getElementById('departamento').addEventListener('change', function() {
    let idDepartamento = this.value;

    fetch('../../core/db/getProvincias.php?id=' + idDepartamento)
        .then(res => res.json())
        .then(data => {
            let provincia = document.getElementById('provincia');
            provincia.innerHTML = '<option value="">Seleccione una provincia</option>';

            data.forEach(p =>{
                provincia.innerHTML += `<option value="${p.idProvincia}">${p.nombre}</option>`;
            });
            
            document.getElementById('distrito').innerHTML = '<option value="">Seleccione un distrito</option>';
        });
});

//CARGAR DISTRITOS

document.getElementById('provincia').addEventListener('change', function() {
    let idProvincia = this.value;

    fetch('../../core/db/getDistritos.php?id=' + idProvincia)
        .then(res => res.json())
        .then(data => {
            let distrito = document.getElementById('distrito');
            distrito.innerHTML = '<option value="">Seleccione un distrito</option>';

            data.forEach(d =>{
                distrito.innerHTML += `<option value="${d.idDistrito}">${d.nombre}</option>`;
            });
        });
});