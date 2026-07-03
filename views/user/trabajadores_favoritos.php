<?php
    require_once __DIR__ . '/../../core/config/session.php';
    iniciarSesion();

    if(!isset($_SESSION['idUsuario'])){
        header('Location: ../auth/login.php');
        exit();
    }

    require_once __DIR__ . '/../../controllers/TrabajadorFavoritoController.php';
    $conTrab = new TrabajadorFavoritoController();
    $trabajadores = $conTrab->obtenerFavoritos();

    require_once __DIR__ . '/../../assets/css/style.php';
    require_once __DIR__ . '/../../assets/css/styles.php';
    require_once __DIR__ . '/../../assets/css/style_profile.php';
    require_once __DIR__ . '/../templates/head.php';
    require_once __DIR__ . '/../templates/header.php';
    require_once __DIR__ . '/../../assets/css/styles_anuncios.php'
?>

<div class="profile-page">

    <section class="profile-hero">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>index.php">Inicio</a> &nbsp;/&nbsp; Mi Perfil
        </div>
        <h1>Trabajadores Guardados</h1>
        <p>Las personas cuyos servicios guardaste para contactar más tarde.</p>
    </section>

    <div class="profile-layout">

        <?php $paginaActual = 'trabajadores'; require_once __DIR__ . '/../templates/profile_sidebar.php'; ?>

        <main class="profile-content">
            <section class="vista-seccion activo" style="display: block;">
                <div class="encabezado-seccion"><h2>Mis Trabajadores</h2></div>

                <?php if (!empty($_GET['estado'])):
                    $msgs = [
                        'trab_quitado' => ['Trabajador quitado de tus guardados.', '#64748b'],
                        'error'        => ['No se pudo procesar la acción.',       '#dc2626'],
                    ];
                    $b = $msgs[$_GET['estado']] ?? null;
                    if ($b): ?>
                        <div style="margin:0 0 15px;padding:12px 18px;border-radius:8px;color:#fff;background:<?= $b[1] ?>;font-weight:600;">
                            <?= htmlspecialchars($b[0]) ?>
                        </div>
                <?php endif; endif; ?>

                <div id="lista-trabajadores">
                    <?php if (empty($trabajadores)): ?>
                        <p>Todavía no guardaste ningún trabajador. Cuando veas un perfil de servicio, usa "Guardar trabajador".</p>
                    <?php else: ?>
                        <?php foreach ($trabajadores as $t): ?>
                            <div class="tarjeta-horizontal" id="tarjeta-trabajador-<?= (int) $t['idUsuarioTrabajador'] ?>">
                                <div class="imagen-tarjeta">
                                    <?php $foto = !empty($t['fotoPerfil']) ? $t['fotoPerfil'] : 'default.png'; ?>
                                    <img src="<?= BASE_URL ?>assets/uploads/img_perfiles/<?= htmlspecialchars($foto) ?>"
                                         alt="" style="width:56px;height:56px;border-radius:50%;object-fit:cover;"
                                         onerror="this.src='<?= BASE_URL ?>assets/uploads/img_perfiles/default.png';">
                                </div>
                                <div class="cuerpo-tarjeta">
                                    <h3>
                                        <a href="<?= BASE_URL ?>controllers/PerfilController.php?id=<?= (int) $t['idUsuarioTrabajador'] ?>" style="color:inherit;text-decoration:none;">
                                            <?= htmlspecialchars($t['nombres'] . ' ' . $t['apellidos']) ?>
                                        </a>
                                    </h3>
                                    <p><?= htmlspecialchars($t['descripcionPerfil'] ?? 'Sin descripción.') ?></p>
                                    <p style="font-size:.85rem;color:#666;">
                                        <i class="fas fa-star" style="color:#ffcc00;"></i> <?= $t['promedio'] !== null ? number_format($t['promedio'],1) : 'Sin calificaciones' ?>
                                        <?php if (!empty($t['telefono'])): ?>
                                            &nbsp;|&nbsp; <i class="fas fa-phone"></i> <?= htmlspecialchars($t['telefono']) ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="acciones-tarjeta">
                                    <?php $wa = linkWhatsApp($t['telefono'] ?? '', 'Hola ' . $t['nombres'] . ', te guardé en Chamba Ya y quiero contactarte.'); ?>
                                    <?php if ($wa): ?>
                                        <a href="<?= htmlspecialchars($wa) ?>" target="_blank" rel="noopener" class="boton-accion" style="background:#25D366;color:#fff;">
                                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="boton-accion" onclick="quitarTrabajadorGuardado(<?= (int) $t['idUsuarioTrabajador'] ?>)">
                                        <i class="fas fa-trash"></i> Quitar
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</div>

<script>const basePath = "<?= BASE_URL ?>";</script>
<script src="<?= BASE_URL ?>assets/js/favoritos_ajax.js"></script>

</body>
</html>
