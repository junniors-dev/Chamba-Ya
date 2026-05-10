<?php require_once 'assets/css/style_header.php'; ?>

<header>
    <div class="main-header">

        <img src="../assets/img/logo-chamba-ya.png" 
            alt="logo_chambaYa" 
            class="logo_web" 
            width="200px">

        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="">Ofertas de Trabajo</a></li>
                <li><a href="">Ofertas de Servicio</a></li>
                <li><a href="">Ofertas cerca de ti</a></li>
            </ul>
        </nav>

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

                        <a href="">Mis datos</a>
                        <a href="">Mis postulaciones</a>
                        <a href="">Anuncios guardados</a>
                        <a href="">Anuncios creados</a>
                        <a href="../pages/logout.php">Cerrar sesión</a>
                    </div>

                <?php else: ?>

                    <!-- Usuario NO logueado -->
                    <button id="userBtn">
                        <i class="fa-regular fa-user"></i>
                    </button>

                    <div class="user_menu" id="userMenu">
                        <a href="../pages/login.php">
                            Iniciar Sesión
                        </a>

                        <a href="../pages/register.php">
                            Registrarse
                        </a>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</header>

<script src="../assets/js/functions_header.js"></script>