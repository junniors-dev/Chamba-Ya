<?php
    session_start();
    require_once '../config.php';
    require_once '../assets/css/style.php';
    require_once '../assets/css/styles.php';
    require_once '../assets/css/style_quienes_somos.php';
    require_once 'templates/head.php';
    require_once 'templates/header.php';
?>

<div class="about-page">
    
    <!-- Hero Section -->
    <section class="about-hero">
        <h1>Quiénes Somos</h1>
        <p>Conectando el talento local con las mejores oportunidades de forma directa, rápida y segura.</p>
    </section>

    <!-- Main Container -->
    <div class="about-container">
        
        <!-- Misión y Visión -->
        <div class="mv-grid">
            <div class="mv-card mission">
                <div class="mv-icon"><i class="fa-solid fa-bullseye"></i></div>
                <h2>Nuestra Misión</h2>
                <p>Nuestra misión es democratizar el acceso a oportunidades laborales, brindando una plataforma tecnológica intuitiva donde trabajadores independientes y empresas puedan conectar de manera transparente, eliminando barreras y fomentando el crecimiento económico local.</p>
            </div>
            
            <div class="mv-card vision">
                <div class="mv-icon"><i class="fa-solid fa-eye"></i></div>
                <h2>Nuestra Visión</h2>
                <p>Ser reconocidos como la red de empleo y servicios más confiable y eficiente del país, creando un ecosistema laboral digital donde cada persona encuentre la oportunidad perfecta para desarrollarse y cada empresa el talento ideal que necesita.</p>
            </div>
        </div>

        <!-- Valores -->
        <div class="values-section">
            <h2>Nuestros Valores</h2>
            <p>Los principios fundamentales que guían cada decisión y mejora que implementamos en Chamba Ya.</p>
            
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3>Confianza</h3>
                    <p>Fomentamos un entorno seguro y transparente para todas las interacciones laborales.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fa-solid fa-bolt"></i></div>
                    <h3>Agilidad</h3>
                    <p>Procesos optimizados para conectar talento y necesidad en tiempo récord.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fa-solid fa-handshake-angle"></i></div>
                    <h3>Comunidad</h3>
                    <p>Crecemos juntos. Creemos en el apoyo mutuo y el trato justo entre todos.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fa-solid fa-lightbulb"></i></div>
                    <h3>Innovación</h3>
                    <p>Mejoramos constantemente nuestra tecnología para brindar la mejor experiencia.</p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="about-cta">
            <h2>¿Listo para transformar tu futuro?</h2>
            <p>Únete a los miles de usuarios que ya están encontrando oportunidades y talento en Chamba Ya.</p>
            <div class="cta-buttons">
                <?php if(isset($_SESSION['idUsuario'])): ?>
                    <a href="<?= $base_path ?>index.php" class="cta-btn-primary">Explorar Ofertas</a>
                    <a href="<?= $base_path ?>controllers/AuthController.php?action=showMisDatos" class="cta-btn-outline">Ir a mi Perfil</a>
                <?php else: ?>
                    <a href="<?= $base_path ?>views/auth/register.php" class="cta-btn-primary">Crear Cuenta Gratis</a>
                    <a href="<?= $base_path ?>views/auth/login.php" class="cta-btn-outline">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
</body>
</html>
