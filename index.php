<?php
    session_start();

    // Capturamos la acción solicitada
    $action = $_GET['action'] ?? 'home';

    if ($action === 'buscar-trabajo') {
        // Derivamos el control a la arquitectura limpia
        require_once 'controllers/AnuncioController.php';
        $controller = new AnuncioController();
        $controller->explorar();
        exit();
    }

    // NUEVA INCLUSIÓN: Capturar el clic de "Ver más"
    if ($action === 'detalle-anuncio') {
        require_once 'controllers/AnuncioController.php';
        $controller = new AnuncioController();
        $controller->verDetalle(); // Reutilizamos el mismo controlador
        exit();
    }



    // =========================================================================
    // SI LA ACCIÓN ES HOME O REFRESH, CARGAMOS LAS CATEGORÍAS DE LA BASE DE DATOS
    // =========================================================================
    require_once 'core/config/autoload.php';
    require_once 'core/config/config.php';
    require_once 'models/anuncioModel.php';
    $model = new AnuncioModel();
    $categoriasBD = $model->obtenerCategorias();

    // SI LA ACCIÓN ES REFRESH O HOME, SE CORRE TU LANDING PAGE ORIGINAL:
    require_once 'assets/css/style.php';
    require_once 'assets/css/styles.php';
    require_once 'views/templates/head.php';
    require_once 'views/templates/header.php';
?>

    <body>
        <div class="container_halfs">
            <div class="left_half">
                <h1>¿Buscas trabajo rápido?</h1>
                <p>Encuentra ofertas cerca de ti y postula al toque. Sin CV, contacto directo</p>
                <img src="<?= BASE_URL ?>assets/img/imagen_left.png" alt="Imagen Left">
                <button class="btn_left_first" onclick="window.location.href='<?= BASE_URL ?>index.php?action=buscar-trabajo&tipo=trabajo'">Ver ofertas de Trabajo</button>
                <button class="btn_left_second" onclick="alert('Funcionalidad en desarrollo')">Publicar mi perfil de servicio</button>
            </div>
            <div class="right_half">
                <h1>¿Necesitas ayuda ahora?</h1>
                <p>Encuentra a trabajadores que te ayuden o publica tu necesidad. Gasfiteres, electricistas, limpieza, jardinero y más.</p>
                <img src="<?= BASE_URL ?>assets/img/imagen_right.png" alt="Imagen Right">
                <button class="btn_right_first" onclick="window.location.href='<?= BASE_URL ?>index.php?action=buscar-trabajo&tipo=servicio'">Buscar trabajadores</button>
                <button class="btn_right_second" onclick="alert('Funcionalidad en desarrollo')">Publicar oferta de trabajo</button>
            </div>
        </div>
        
    <div class="categories">
        <h2>Categorías:</h2>
        <div class="categories_carousel">
            <button class="carousel_btn prev" onclick="scrollCarousel(-1)">&#10094;</button>
            <div class="carousel_wrapper" id="carouselWrapper">
                
                <?php if (!empty($categoriasBD)): ?>
                    <?php foreach ($categoriasBD as $cat): 
                        // Cambiado a $cat['nombre'] para coincidir con tu phpMyAdmin
                        $nombreLimpio = strtolower(trim($cat['nombre']));
                        
                        // Reemplazamos acentos y espacios para que busque las imágenes correctamente
                        $buscar = array('á', 'é', 'í', 'ó', 'ú', 'ñ', ' ', 'í');
                        $reemplazar = array('a', 'e', 'i', 'o', 'u', 'n', '_', 'i');
                        $archivoImagen = str_replace($buscar, $reemplazar, $nombreLimpio) . ".png";
                    ?>
                        <a href="index.php?action=buscar-trabajo&categoria=<?= urlencode($cat['nombre']) ?>" class="categories_card">
                            <img src="<?= BASE_URL ?>assets/img/<?= $archivoImagen ?>" alt="<?= htmlspecialchars($cat['nombre']) ?>" onerror="this.src='<?= BASE_URL ?>assets/img/servicios_varios.png';">
                            <h3><?= htmlspecialchars($cat['nombre']) ?></h3>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="" class="categories_card">
                        <img src="<?= BASE_URL ?>assets/img/servicios_varios.png" alt="Sin categorías">
                        <h3>No hay categorías registradas</h3>
                    </a>
                <?php endif; ?>
            </div>
            <button class="carousel_btn next" onclick="scrollCarousel(1)">&#10095;</button>
        </div>
    </div>

    <div class="why_chamba_ya">
        <h1>¿Por qué usar "Chamba Ya"?</h1>
        <div class="why_cards_container">
            <div class="why_card">
                <img src="<?= BASE_URL ?>assets/img/no_cv.png" alt="SIN CV">
                <h2>SIN CV</h2>
                <p>No necesitas tener curriculum</p>
            </div>
            <div class="why_card">
                <img src="<?= BASE_URL ?>assets/img/contacto_directo.png" alt="CONTACTO DIRECTO">
                <h2>CONTACTO DIRECTO</h2>
                <p>Contacta directamente con los creadores del puesto</p>
            </div>
            <div class="why_card">
                <img src="<?= BASE_URL ?>assets/img/trabajo_rapido.png" alt="TRABAJOS RÁPIDOS">
                <h2>TRABAJOS RÁPIDOS</h2>
                <p>Consigue un trabajo rápido según tus habilidades</p>
            </div>
        </div>
    </div>
    <?php require_once __DIR__ . '/views/templates/footer.php'; ?>
    </body>
    <script src="<?= BASE_URL ?>assets/js/index.js"></script>
    </html>