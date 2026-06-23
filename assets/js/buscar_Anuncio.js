document.addEventListener("DOMContentLoaded", function () {
    const depSelect = document.getElementById("filtroDepartamento");
    const provSelect = document.getElementById("filtroProvincia");
    const distSelect = document.getElementById("filtroDistrito");
    const formGlobal = document.getElementById("formFiltrosGlobal");

    // ==========================================
    // RECUPERAR POSICIÓN DEL SCROLL
    // ==========================================
    const posicionGuardada = localStorage.getItem("scrollFiltrosChambaYa");
    if (posicionGuardada) {
        window.scrollTo(0, parseInt(posicionGuardada, 10));
        localStorage.removeItem("scrollFiltrosChambaYa"); 
    }

    function enviarFormularioConScroll() {
        localStorage.setItem("scrollFiltrosChambaYa", window.scrollY);
        formGlobal.submit();
    }

    // 1. Al cargar la página, si PHP detecta departamento seleccionado, cargamos las provincias y distritos guardados
    if (depSelect && depSelect.value) {
        cargarProvincias(depSelect.value, idProvinciaSeleccionada);
    }

    // 2. Evento cuando cambia el Departamento
    if (depSelect) {
        depSelect.addEventListener("change", function () {
            const idDep = this.value;

            provSelect.innerHTML = '<option value="">Provincia</option>';
            distSelect.innerHTML = '<option value="">Distrito</option>';

            if (!idDep) {
                enviarFormularioConScroll();
                return;
            }
            cargarProvincias(idDep, "", !idProvinciaSeleccionada);
        });
    }

    // 3. Evento cuando cambia la Provincia
    if (provSelect) {
        provSelect.addEventListener("change", function () {
            const idProv = this.value;
            distSelect.innerHTML = '<option value="">Distrito</option>';
            
            if (!idProv) {
                enviarFormularioConScroll();
                return;
            }
            cargarDistritos(idProv, "", true);
        });
    }

    // Función para cargar provincias mediante AJAX
    function cargarProvincias(idDep, provIdSeleccionada, enviarAlTerminar = false) {
        fetch(`${BASE_URL}core/db/getProvincias.php?id=${idDep}`)
            .then(res => res.json())
            .then(data => {
                provSelect.innerHTML = '<option value="">Provincia</option>';
                data.forEach(p => {
                    const selected = (p.idProvincia == provIdSeleccionada) ? 'selected' : '';
                    provSelect.innerHTML += `<option value="${p.idProvincia}" ${selected}>${p.nombre}</option>`;
                });

                if (provIdSeleccionada) {
                    cargarDistritos(provIdSeleccionada, idDistritoSeleccionado);
                } else if (enviarAlTerminar) {
                    enviarFormularioConScroll();
                }
            })
            .catch(err => console.error("Error cargando provincias:", err));
    }

    // Función para cargar distritos mediante AJAX
    function cargarDistritos(idProv, distIdSeleccionado, enviarAlTerminar = false) {
        fetch(`${BASE_URL}core/db/getDistritos.php?id=${idProv}`)
            .then(res => res.json())
            .then(data => {
                distSelect.innerHTML = '<option value="">Distrito</option>';
                data.forEach(d => {
                    const selected = (d.idDistrito == distIdSeleccionado) ? 'selected' : '';
                    distSelect.innerHTML += `<option value="${d.idDistrito}" ${selected}>${d.nombre}</option>`;
                });

                if (enviarAlTerminar) {
                    enviarFormularioConScroll();
                }
            })
            .catch(err => console.error("Error cargando distritos:", err));
    }

     window.enviarFormularioConScroll = enviarFormularioConScroll;
});