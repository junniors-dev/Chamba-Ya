// ===== CARGAR PREFERENCIAS AL INICIO =====
function cargarPreferencias() {
    const saved = JSON.parse(localStorage.getItem(PREF_KEY) || '{}');
    document.getElementById('notif_ofertas').checked  = saved.notif_ofertas  ?? true;
    document.getElementById('notif_vistas').checked   = saved.notif_vistas   ?? true;
    document.getElementById('notif_boletin').checked  = saved.notif_boletin  ?? false;
    document.getElementById('visibilidad').value      = saved.visibilidad    ?? 'publico';
}

// ===== GUARDAR PREFERENCIAS =====
function guardarPreferencias() {
    const prefs = {
        notif_ofertas:  document.getElementById('notif_ofertas').checked,
        notif_vistas:   document.getElementById('notif_vistas').checked,
        notif_boletin:  document.getElementById('notif_boletin').checked,
        visibilidad:    document.getElementById('visibilidad').value,
    };
    localStorage.setItem(PREF_KEY, JSON.stringify(prefs));

    // Mostrar alerta de éxito
    const alert = document.getElementById('prefSavedAlert');
    alert.style.display = 'flex';
    setTimeout(() => { alert.style.display = 'none'; }, 4000);
}

// ===== RESTABLECER A DEFAULTS =====
function resetearPreferencias() {
    localStorage.removeItem(PREF_KEY);
    cargarPreferencias();
    const alert = document.getElementById('prefSavedAlert');
    alert.style.display = 'flex';
    alert.innerHTML = '<i class="fa-solid fa-rotate-left"></i> Preferencias restablecidas a los valores predeterminados.';
    setTimeout(() => {
        alert.style.display = 'none';
        alert.innerHTML = '<i class="fa-solid fa-circle-check"></i> Preferencias guardadas correctamente.';
    }, 4000);
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    if (typeof PREF_KEY !== 'undefined') {
        cargarPreferencias();
    }
});
