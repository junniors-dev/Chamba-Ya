<?php
// Sidebar del perfil (reutilizable).
// Definir $paginaActual antes de incluirlo para resaltar el ítem correspondiente.
$paginaActual = $paginaActual ?? '';
if (!function_exists('navActiva')) {
    function navActiva($clave, $actual) {
        return $clave === $actual ? 'class="active"' : '';
    }
}
?>
<?php
    // Modo activo: reordena la navegación según lo que la persona está haciendo
    // ahora. No le quita ninguna opción, solo pone delante las que le sirven.
    $modo = function_exists('modoActivo') ? modoActivo() : 'trabajador';
    $urlActual = $_SERVER['REQUEST_URI'] ?? '';
?>
<aside class="profile-sidebar">
    <div class="profile-sidebar-card">

        <!-- ===== Conmutador de modo ===== -->
        <div class="sidebar-section modo-switch">
            <h4>Estoy aquí para</h4>
            <form method="POST" action="<?= BASE_URL ?>controllers/AuthController.php?action=cambiarModo">
                <?= campoCsrf() ?>
                <input type="hidden" name="volver_a" value="<?= htmlspecialchars($urlActual, ENT_QUOTES, 'UTF-8') ?>">
                <div class="modo-opciones">
                    <button type="submit" name="modo" value="trabajador"
                            class="modo-btn <?= $modo === 'trabajador' ? 'modo-activo' : '' ?>"
                            <?= $modo === 'trabajador' ? 'aria-current="true"' : '' ?>>
                        <i class="fa-solid fa-helmet-safety"></i> Buscar chamba
                    </button>
                    <button type="submit" name="modo" value="cliente"
                            class="modo-btn <?= $modo === 'cliente' ? 'modo-activo' : '' ?>"
                            <?= $modo === 'cliente' ? 'aria-current="true"' : '' ?>>
                        <i class="fa-solid fa-handshake-angle"></i> Contratar
                    </button>
                </div>
            </form>
        </div>

        <div class="sidebar-section">
            <h4>Ajustes</h4>
            <ul class="sidebar-nav">
                <li><a <?= navActiva('mis_datos', $paginaActual) ?> href="<?= BASE_URL ?>controllers/AuthController.php?action=showMisDatos"><i class="fa-solid fa-user"></i> Mis Datos</a></li>
                <?php if ($modo === 'trabajador'): ?>
                    <li><a <?= navActiva('guardados', $paginaActual) ?> href="<?= BASE_URL ?>views/user/mis_guardados.php"><i class="fa-regular fa-bookmark"></i> Anuncios guardados</a></li>
                    <li><a <?= navActiva('trabajadores', $paginaActual) ?> href="<?= BASE_URL ?>views/user/trabajadores_favoritos.php"><i class="fa-regular fa-heart"></i> Trabajadores guardados</a></li>
                <?php else: ?>
                    <li><a <?= navActiva('trabajadores', $paginaActual) ?> href="<?= BASE_URL ?>views/user/trabajadores_favoritos.php"><i class="fa-regular fa-heart"></i> Trabajadores guardados</a></li>
                    <li><a <?= navActiva('guardados', $paginaActual) ?> href="<?= BASE_URL ?>views/user/mis_guardados.php"><i class="fa-regular fa-bookmark"></i> Anuncios guardados</a></li>
                <?php endif; ?>
                <li><a <?= navActiva('anuncios', $paginaActual) ?> href="<?= BASE_URL ?>views/user/mis_anuncios.php"><i class="fa-regular fa-square-plus"></i> Anuncios creados</a></li>
            </ul>
        </div>
        <div class="sidebar-section">
            <h4>Actividad</h4>
            <ul class="sidebar-nav">
                <?php if ($modo === 'trabajador'): ?>
                    <li><a <?= navActiva('postulaciones', $paginaActual) ?> href="<?= BASE_URL ?>views/user/mis_postulaciones.php"><i class="fa-solid fa-paper-plane"></i> Mis Postulaciones</a></li>
                    <li><a <?= navActiva('recibidas', $paginaActual) ?> href="<?= BASE_URL ?>views/user/postulaciones_recibidas.php"><i class="fa-solid fa-inbox"></i> Solicitudes recibidas</a></li>
                <?php else: ?>
                    <li><a <?= navActiva('recibidas', $paginaActual) ?> href="<?= BASE_URL ?>views/user/postulaciones_recibidas.php"><i class="fa-solid fa-inbox"></i> Solicitudes recibidas</a></li>
                    <li><a <?= navActiva('postulaciones', $paginaActual) ?> href="<?= BASE_URL ?>views/user/mis_postulaciones.php"><i class="fa-solid fa-paper-plane"></i> Mis Postulaciones</a></li>
                <?php endif; ?>
                <li><a <?= navActiva('notificaciones', $paginaActual) ?> href="<?= BASE_URL ?>views/user/notificaciones.php"><i class="fa-regular fa-bell"></i> Notificaciones</a></li>
                <li><a <?= navActiva('historial', $paginaActual) ?> href="<?= BASE_URL ?>views/user/mi_historial.php"><i class="fa-solid fa-history"></i> Historial</a></li>
            </ul>
        </div>
        <div class="sidebar-section">
            <h4>Seguridad</h4>
            <ul class="sidebar-nav">
                <li><a <?= navActiva('seguridad', $paginaActual) ?> href="<?= BASE_URL ?>controllers/AuthController.php?action=showSeguridad"><i class="fa-solid fa-shield-halved"></i> Seguridad</a></li>
                <li><a <?= navActiva('preferencias', $paginaActual) ?> href="<?= BASE_URL ?>controllers/AuthController.php?action=showPreferencias"><i class="fa-solid fa-sliders"></i> Preferencias</a></li>
            </ul>
        </div>
    </div>
</aside>
