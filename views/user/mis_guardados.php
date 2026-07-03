<?php
    require_once __DIR__ . '/../../core/config/session.php';
    iniciarSesion();
    
    if(!isset($_SESSION['idUsuario'])){
        header('Location: ../auth/login.php');
        exit();
    }
    
    require_once __DIR__ . '/../../controllers/AnuncioGuardadoController.php';
    $conGuardado = new AnuncioGuardadoController();
    $anuncios_favoritos = $conGuardado->obtenerAnunciosFavoritos();

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
        <h1>Anuncios Guardados</h1>
        <p>Tus ofertas y servicios favoritos.</p>
    </section>

    <!-- Layout -->
    <div class="profile-layout">

        <!-- Sidebar -->
        <?php $paginaActual = 'guardados'; require_once __DIR__ . '/../templates/profile_sidebar.php'; ?>

        <!-- Content -->
        <main class="profile-content">
            <!-- SECCIÓN: GUARDADOS -->
            <section id="seccion-guardados" class="vista-seccion activo" style="display: block;">
                <div class="encabezado-seccion"><h2>Anuncios Guardados</h2></div>
                <?php if (($_GET['estado'] ?? '') === 'fav_quitado'): ?>
                    <div style="margin:0 0 15px;padding:12px 18px;border-radius:8px;color:#fff;background:#64748b;font-weight:600;">
                        Anuncio quitado de tus guardados.
                    </div>
                <?php endif; ?>
                <div id="lista-anuncios-guardados">
                    <?php if (empty($anuncios_favoritos)): ?>
                        <p>Actualmente no tienes anuncios guardados</p>
                    <?php else: ?>
                        <?php foreach ($anuncios_favoritos as $fav): ?>
                            <div class="tarjeta-horizontal" id="tarjeta-guardado-<?= (int) $fav['id'] ?>">
                                <div class="imagen-tarjeta"><i class="fas fa-bookmark fa-2x" style="color: var(--purpura-principal);"></i></div>
                                <div class="cuerpo-tarjeta">
                                    <h3><?php echo htmlspecialchars($fav['titulo']); ?></h3>
                                    <p><?php echo htmlspecialchars($fav['descripcion']); ?></p>
                                    <span class="etiqueta-estado estado-<?php echo str_replace(' ', '', $fav['estado']); ?>">
                                        <?php echo ucfirst($fav['estado']); ?>
                                    </span>
                                    <?php if (!empty($fav['categorias_nombres'])): ?>
                                        <div style="margin-top: 8px; display: flex; gap: 5px; flex-wrap: wrap;">
                                            <?php foreach (explode(',', $fav['categorias_nombres']) as $cat_nom): ?>
                                                <span class="etiqueta-estado estado-info" style="font-size: 0.7rem; padding: 2px 8px;">
                                                    <?php echo htmlspecialchars($cat_nom); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="acciones-tarjeta">
                                    <button type="button" class="boton-accion" onclick="quitarAnuncioGuardado(<?= (int) $fav['id'] ?>)">
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
<script src="<?= BASE_URL ?>assets/js/script_Anuncios.js"></script>

</body>
</html>
