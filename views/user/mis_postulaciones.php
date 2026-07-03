<?php
    require_once __DIR__ . '/../../core/config/session.php';
    iniciarSesion();
    
    if(!isset($_SESSION['idUsuario'])){
        header('Location: ../auth/login.php');
        exit();
    }
    
    require_once __DIR__ . '/../../controllers/PostulacionController.php';
    $conPostulacion = new PostulacionController();
    $mis_postulaciones = $conPostulacion->obtenerPostulaciones();

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
        <h1>Mis Postulaciones</h1>
        <p>Revisa el estado de los trabajos a los que has aplicado.</p>
    </section>

    <!-- Layout -->
    <div class="profile-layout">

        <!-- Sidebar -->
        <?php $paginaActual = 'postulaciones'; require_once __DIR__ . '/../templates/profile_sidebar.php'; ?>

        <!-- Content -->
        <main class="profile-content">
            <!-- SECCIÓN: MIS POSTULACIONES -->
            <section id="seccion-postulaciones" class="vista-seccion activo" style="display: block;">
                <div class="encabezado-seccion"><h2>Mis Postulaciones</h2></div>
                <div id="lista-postulaciones">
                    <?php if (empty($mis_postulaciones)): ?>
                            <p>No tienes postulaciones activas.</p>
                    <?php else: ?>
                        <?php foreach ($mis_postulaciones as $postulacion): ?>
                            <div class="tarjeta-horizontal">
                                <div class="imagen-tarjeta"><i class="fas fa-briefcase"></i></div>
                                <div class="cuerpo-tarjeta">
                                    <h3><?php echo htmlspecialchars($postulacion['puesto']); ?></h3>
                                    <p>Postulado el <?php echo date('d M Y', strtotime($postulacion['fecha'])); ?></p>
                                    <span class="etiqueta-estado estado-<?php echo $postulacion['estado']; ?>">
                                        <?php echo ucfirst($postulacion['estado']); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>   
                    <?php endif; ?>                   
                </div>
            </section>
        </main>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/js/script_Anuncios.js?v=<?php echo time(); ?>"></script>

</body>
</html>
