<?php require_once __DIR__ . '/../../assets/css/style_header.php'; ?>
<?php require_once __DIR__ . '/../../core/config/config.php'; ?>
<?php
    $notifNoLeidas = 0;
    $notifRecientes = [];
    if (isset($_SESSION['idUsuario'])) {
        require_once __DIR__ . '/../../models/NotificacionModel.php';
        $nmHeader = new NotificacionModel();
        $notifNoLeidas = $nmHeader->contarNoLeidas($_SESSION['idUsuario']);
        $notifRecientes = $nmHeader->obtenerDeUsuario($_SESSION['idUsuario'], 8);
    }
?>

<header>
    <div class="main-header">

        <img src="<?= BASE_URL; ?>assets/img/logo-chamba-ya.png" 
            alt="logo_chambaYa" 
            class="logo_web" 
            width="200px">

        <button class="menu-toggle" aria-label="Abrir menú">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </button>

        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="<?= BASE_URL; ?>index.php">Inicio</a></li>
                <li><a href="<?= BASE_URL; ?>index.php?action=buscar-trabajo&tipo=trabajo">Ofertas de Trabajo</a></li>
                <li><a href="<?= BASE_URL; ?>index.php?action=buscar-trabajo&tipo=servicio">Ofertas de Servicio</a></li>
            </ul>

            <div class="actions">

            <?php
                // El buscador respeta el tipo que estás viendo (trabajo o servicio)
                $tipoBusquedaHeader = in_array($_GET['tipo'] ?? '', ['trabajo', 'servicio']) ? $_GET['tipo'] : 'trabajo';
            ?>
            <form class="search_box" method="GET" action="<?= BASE_URL ?>index.php" role="search">
                <input type="hidden" name="action" value="buscar-trabajo">
                <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipoBusquedaHeader) ?>">
                <input type="text" name="search" placeholder="<?= $tipoBusquedaHeader === 'servicio' ? 'Buscar servicios...' : 'Buscar trabajos...' ?>"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" aria-label="Buscar"
                    style="position:absolute; right:15px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; padding:0; color:#000;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <div class="user_box">

                <?php if(isset($_SESSION['idUsuario'])): ?>

                    <!-- Campana de notificaciones (desplegable) -->
                    <div class="notif_box">
                        <button id="notifBtn" class="notif-bell" aria-label="Notificaciones"
                                data-marcar="<?= BASE_URL ?>controllers/NotificacionController.php">
                            <i class="fa-regular fa-bell"></i>
                            <?php if ($notifNoLeidas > 0): ?>
                                <span class="notif-badge"><?= $notifNoLeidas > 9 ? '9+' : $notifNoLeidas ?></span>
                            <?php endif; ?>
                        </button>

                        <div class="notif_menu" id="notifMenu">
                            <div class="notif_menu_header">Notificaciones</div>
                            <?php if (empty($notifRecientes)): ?>
                                <div class="notif_empty">No tienes notificaciones</div>
                            <?php else: ?>
                                <?php foreach ($notifRecientes as $n): ?>
                                    <a class="notif_item <?= $n['leida'] ? '' : 'no-leida' ?>"
                                       href="<?= !empty($n['link']) ? BASE_URL . htmlspecialchars($n['link']) : BASE_URL . 'views/user/notificaciones.php' ?>">
                                        <i class="fa-regular fa-bell"></i>
                                        <div>
                                            <span><?= htmlspecialchars($n['mensaje']) ?></span>
                                            <small><?= date('d M H:i', strtotime($n['fecha'])) ?></small>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <a class="notif_ver_todas" href="<?= BASE_URL ?>views/user/notificaciones.php">Ver todas</a>
                        </div>
                    </div>

                    <!-- Usuario logueado -->
                    <button id="userBtn">
                        <i class="fa-regular fa-user"></i>
                    </button>

                    <div class="user_menu" id="userMenu">
                        <p>
                            Bienvenido
                            <?= htmlspecialchars($_SESSION['nombres']); ?>
                        </p>

                        <a href="<?= BASE_URL ?>controllers/AuthController.php?action=showMisDatos">Mis datos</a>
                        <a href="<?= BASE_URL ?>views/user/mis_postulaciones.php">Mis postulaciones</a>
                        <a href="<?= BASE_URL ?>views/user/mis_guardados.php">Anuncios guardados</a>
                        <a href="<?= BASE_URL ?>views/user/mis_anuncios.php">Anuncios creados</a>
                        <a href="<?= BASE_URL; ?>views/auth/logout.php">Cerrar sesión</a>
                    </div>

                <?php else: ?>

                    <!-- Usuario NO logueado -->
                    <button id="userBtn">
                        <i class="fa-regular fa-user"></i>
                    </button>

                    <div class="user_menu" id="userMenu">
                        <a href="<?= BASE_URL; ?>views/auth/login.php">
                            Iniciar Sesión
                        </a>

                        <a href="<?= BASE_URL; ?>views/auth/login.php">
                            Registrarse
                        </a>
                    </div>

                <?php endif; ?>

                </div>
            </div>
        </nav>

        
    </div>
</header>

<script src="<?= BASE_URL; ?>assets/js/functions_header.js?v=<?php echo time(); ?>"></script>