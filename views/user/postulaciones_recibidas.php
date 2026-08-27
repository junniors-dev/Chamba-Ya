<?php
    require_once __DIR__ . '/../../core/config/session.php';
    iniciarSesion();

    if(!isset($_SESSION['idUsuario'])){
        header('Location: ../auth/login.php');
        exit();
    }

    require_once __DIR__ . '/../../controllers/PostulacionController.php';
    $conPostulacion = new PostulacionController();
    $recibidas = $conPostulacion->obtenerRecibidas();

    require_once __DIR__ . '/../../assets/css/style.php';
    require_once __DIR__ . '/../../assets/css/styles.php';
    require_once __DIR__ . '/../../assets/css/style_profile.php';
    require_once __DIR__ . '/../templates/head.php';
    require_once __DIR__ . '/../templates/header.php';
    require_once __DIR__ . '/../../assets/css/styles_anuncios.php'
?>

<div class="profile-page">

    <!-- Hero -->
    <section class="profile-hero">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>index.php">Inicio</a> &nbsp;/&nbsp; Mi Perfil
        </div>
        <h1>Postulaciones Recibidas</h1>
        <p>Gestiona a las personas que se postularon a tus anuncios.</p>
    </section>

    <!-- Layout -->
    <div class="profile-layout">

        <!-- Sidebar -->
        <?php $paginaActual = 'recibidas'; require_once __DIR__ . '/../templates/profile_sidebar.php'; ?>

        <!-- Content -->
        <main class="profile-content">
            <section class="vista-seccion activo" style="display: block;">
                <div class="encabezado-seccion"><h2>Postulaciones a mis anuncios</h2></div>

                <?php if (!empty($_GET['estado'])):
                    $msgs = [
                        'aceptada'  => ['Postulación aceptada.',  '#16a34a'],
                        'rechazada' => ['Postulación rechazada.', '#64748b'],
                        'error'     => ['No se pudo procesar la acción.', '#dc2626'],
                    ];
                    $b = $msgs[$_GET['estado']] ?? null;
                    if ($b): ?>
                        <div style="margin:0 0 15px;padding:12px 18px;border-radius:8px;color:#fff;background:<?= $b[1] ?>;font-weight:600;">
                            <?= htmlspecialchars($b[0]) ?>
                        </div>
                <?php endif; endif; ?>

                <div id="lista-recibidas">
                    <?php if (empty($recibidas)): ?>
                        <p>Todavía no has recibido postulaciones.</p>
                    <?php else: ?>
                        <?php foreach ($recibidas as $p): ?>
                            <div class="tarjeta-horizontal">
                                <div class="imagen-tarjeta">
                                    <?php if (!empty($p['fotoPerfil'])): ?>
                                        <img src="<?= BASE_URL ?>assets/uploads/img_perfiles/<?= htmlspecialchars($p['fotoPerfil']) ?>"
                                             alt="Foto de <?= htmlspecialchars($p['nombres']) ?>"
                                             style="width:100%;height:100%;object-fit:cover;border-radius:50%;"
                                             onerror="this.src='<?= BASE_URL ?>assets/uploads/img_perfiles/default.png'">
                                    <?php else: ?>
                                        <i class="fas fa-user"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="cuerpo-tarjeta">
                                    <h3><?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?></h3>
                                    <p>Se postuló a: <strong><?= htmlspecialchars($p['puesto']) ?></strong></p>
                                    <p style="font-size:.85rem;color:#666;">
                                        <i class="fas fa-phone"></i> <?= htmlspecialchars($p['telefono'] ?? 'No especificado') ?>
                                        &nbsp;|&nbsp;
                                        <i class="fas fa-envelope"></i> <?= htmlspecialchars($p['correo']) ?>
                                    </p>
                                    <p style="font-size:.8rem;color:#999;">Postuló el <?= date('d M Y', strtotime($p['fecha'])) ?></p>
                                    <span class="etiqueta-estado estado-<?= htmlspecialchars($p['estado']) ?>">
                                        <?= ucfirst(htmlspecialchars($p['estado'])) ?>
                                    </span>
                                </div>
                                <div class="acciones-tarjeta">
                                    <?php if ($p['estado'] === 'Pendiente'): ?>
                                        <form action="<?= BASE_URL ?>controllers/PostulacionController.php" method="POST" style="display:inline;">
                    <?= campoCsrf() ?>
                                            <input type="hidden" name="accion" value="gestionar">
                                            <input type="hidden" name="decision" value="aceptar">
                                            <input type="hidden" name="idPostulacion" value="<?= (int) $p['idPostulacion'] ?>">
                                            <button class="boton-accion" type="submit" title="Aceptar"><i class="fas fa-check"></i> Aceptar</button>
                                        </form>
                                        <form action="<?= BASE_URL ?>controllers/PostulacionController.php" method="POST" style="display:inline;">
                    <?= campoCsrf() ?>
                                            <input type="hidden" name="accion" value="gestionar">
                                            <input type="hidden" name="decision" value="rechazar">
                                            <input type="hidden" name="idPostulacion" value="<?= (int) $p['idPostulacion'] ?>">
                                            <button class="boton-accion" type="submit" title="Rechazar"
                                                onclick="return confirm('¿Rechazar esta postulación?');"><i class="fas fa-times"></i> Rechazar</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color:#94a3b8;font-size:.85rem;">Ya gestionada</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</div>

</body>
</html>
