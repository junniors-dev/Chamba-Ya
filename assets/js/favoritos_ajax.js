// Favoritos vía AJAX: alterna "guardado" sin recargar la página.

function toggleFavoritoAnuncio(idAnuncio, boton) {
    if (boton.disabled) return;
    boton.disabled = true;

    const formData = new FormData();
    formData.append('idAnuncio', idAnuncio);
    formData.append('ajax', '1');

    fetch(basePath + 'controllers/AnuncioGuardadoController.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-Token': CSRF_TOKEN }
    })
        .then(res => res.json())
        .then(data => {
            if (!data.ok) {
                alert('No se pudo actualizar. Intenta de nuevo.');
                return;
            }
            boton.textContent = data.esFavorito ? 'Quitar de Favoritos' : 'Añadir a Favoritos';
        })
        .catch(() => alert('Error de conexión. Intenta de nuevo.'))
        .finally(() => { boton.disabled = false; });
}

function toggleFavoritoTrabajador(idTrabajador, boton, idAnuncio) {
    if (boton.disabled) return;
    boton.disabled = true;

    const formData = new FormData();
    formData.append('idTrabajador', idTrabajador);
    formData.append('ajax', '1');
    if (idAnuncio) formData.append('idAnuncio', idAnuncio);

    fetch(basePath + 'controllers/TrabajadorFavoritoController.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-Token': CSRF_TOKEN }
    })
        .then(res => res.json())
        .then(data => {
            if (!data.ok) {
                alert(data.estado === 'trab_propio' ? 'No puedes guardarte a ti mismo.' : 'No se pudo actualizar. Intenta de nuevo.');
                return;
            }
            boton.innerHTML = '<i class="fa-regular fa-heart"></i> ' + (data.esFavorito ? 'Quitar de mis trabajadores' : 'Guardar trabajador');
        })
        .catch(() => alert('Error de conexión. Intenta de nuevo.'))
        .finally(() => { boton.disabled = false; });
}

// Para las listas (mis guardados / mis trabajadores): quita y elimina la tarjeta del DOM sin recargar.
function quitarDeListaAjax(url, campos, tarjeta) {
    const formData = new FormData();
    Object.keys(campos).forEach(k => formData.append(k, campos[k]));
    formData.append('ajax', '1');

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-Token': CSRF_TOKEN }
    })
        .then(res => res.json())
        .then(data => {
            if (!data.ok) {
                alert('No se pudo quitar. Intenta de nuevo.');
                return;
            }
            tarjeta.style.transition = 'opacity .2s ease';
            tarjeta.style.opacity = '0';
            setTimeout(() => tarjeta.remove(), 200);
        })
        .catch(() => alert('Error de conexión. Intenta de nuevo.'));
}

// Wrappers usados en las listas "Mis guardados" / "Mis trabajadores"
function quitarAnuncioGuardado(idAnuncio) {
    if (!confirm('¿Quitar este anuncio de guardados?')) return;
    const tarjeta = document.getElementById('tarjeta-guardado-' + idAnuncio);
    if (!tarjeta) return;
    quitarDeListaAjax(basePath + 'controllers/AnuncioGuardadoController.php', { idAnuncio: idAnuncio, origen: 'guardados' }, tarjeta);
}

function quitarTrabajadorGuardado(idTrabajador) {
    if (!confirm('¿Quitar este trabajador?')) return;
    const tarjeta = document.getElementById('tarjeta-trabajador-' + idTrabajador);
    if (!tarjeta) return;
    quitarDeListaAjax(basePath + 'controllers/TrabajadorFavoritoController.php', { idTrabajador: idTrabajador, origen: 'lista' }, tarjeta);
}
