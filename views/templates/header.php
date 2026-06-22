<?php require_once __DIR__ . '/../../assets/css/style_header.php'; ?>
<?php require_once __DIR__ . '/../../core/config/config.php'; ?>

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
                <li><a href="<?= BASE_URL; ?>views/offers/job_offers.php">Ofertas de Trabajo</a></li>
                <li><a href="<?= BASE_URL; ?>views/offers/service_offers.php">Ofertas de Servicio</a></li>
                <li><a href="<?= BASE_URL; ?>views/offers/nearby_offers.php">Ofertas cerca de ti</a></li>
            </ul>

            <div class="actions">

            <div class="search_box">
                <input type="text" placeholder="Buscar...">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>

            <div class="user_box">

                <?php if(isset($_SESSION['idUsuario'])): ?>

                    <!-- Usuario logueado -->
                    <button id="userBtn">
                        <i class="fa-regular fa-user"></i>
                    </button>

                    <div class="user_menu" id="userMenu">
                        <p>
                            Bienvenido 
                            <?= $_SESSION['nombres']; ?>
                        </p>

                        <a href="<?= BASE_URL; ?>views/user/profile.php">Mis datos</a>
                        <a href="<?= BASE_URL; ?>views/user/applications.php">Mis postulaciones</a>
                        <a href="<?= BASE_URL; ?>views/user/saved_ads.php">Anuncios guardados</a>
                        <a href="<?= BASE_URL; ?>views/user/created_ads.php">Anuncios creados</a>
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

<script src="<?= BASE_URL; ?>assets/js/functions_header.js"></script>