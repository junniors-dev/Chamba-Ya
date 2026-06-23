<?php
    session_start();
    require_once __DIR__ . '/../../core/config/config.php';
    require_once __DIR__ . '/../../assets/css/style.php';
    require_once __DIR__ . '/../../assets/css/styles.php';
    require_once __DIR__ . '/../../assets/css/style_terminos.php';
    require_once __DIR__ . '/../templates/head.php';
    require_once __DIR__ . '/../templates/header.php';
?>

<div class="terms-page">
    <!-- Barra de progreso de lectura -->
    <div class="reading-progress"><div class="reading-progress-bar" id="readingProgress"></div></div>

    <!-- Hero Section -->
    <section class="terms-hero">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>index.php">Inicio</a> &nbsp;/&nbsp; Términos y Condiciones
        </div>
        <h1>Términos y Condiciones</h1>
        <p>Todo lo que necesitas saber sobre cómo funciona Chamba Ya y las reglas de convivencia de nuestra comunidad.</p>
        <span class="last-updated"><i class="fa-regular fa-calendar"></i>&nbsp; Última actualización: Junio 2025</span>
    </section>

    <!-- Main Layout -->
    <div class="terms-layout">
        <!-- Sidebar -->
        <aside class="terms-sidebar">
            <div class="terms-sidebar-card">
                <h3>Contenido</h3>
                <ul>
                    <li>
                        <a href="#resumen" class="active" data-target="#resumen">
                            <span class="sidebar-icon"><i class="fa-solid fa-book-open"></i></span>
                            Resumen general
                        </a>
                    </li>
                    <li>
                        <a href="#conexion" data-target="#conexion">
                            <span class="sidebar-icon"><i class="fa-solid fa-link"></i></span>
                            Puente de conexión
                        </a>
                    </li>
                    <li>
                        <a href="#pagos" data-target="#pagos">
                            <span class="sidebar-icon"><i class="fa-solid fa-credit-card"></i></span>
                            Trato y pagos
                        </a>
                    </li>
                    <li>
                        <a href="#compromiso" data-target="#compromiso">
                            <span class="sidebar-icon"><i class="fa-solid fa-handshake"></i></span>
                            Compromiso del usuario
                        </a>
                    </li>
                    <li>
                        <a href="#privacidad" data-target="#privacidad">
                            <span class="sidebar-icon"><i class="fa-solid fa-shield-halved"></i></span>
                            Privacidad de datos
                        </a>
                    </li>
                    <li>
                        <a href="#reportes" data-target="#reportes">
                            <span class="sidebar-icon"><i class="fa-solid fa-flag"></i></span>
                            Reportes
                        </a>
                    </li>
                    <li>
                        <a href="#seguridad" data-target="#seguridad">
                            <span class="sidebar-icon"><i class="fa-solid fa-lock"></i></span>
                            Seguridad
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Content -->
        <main class="terms-content">
            <div class="terms-card">

                <!-- Sección 1: Resumen -->
                <section class="terms-section" id="resumen">
                    <div class="section-head">
                        <span class="section-number">1</span>
                        <h2>Resumen General</h2>
                    </div>
                    <p>Bienvenido a la comunidad de <strong>Chamba Ya</strong>. Al utilizar nuestra plataforma, aceptas las siguientes reglas de convivencia y uso. Hemos redactado estos términos de forma clara y directa para que cualquiera pueda entenderlos.</p>
                    <div class="highlight-box">
                        <p><i class="fa-solid fa-lightbulb"></i>&nbsp; En resumen: Chamba Ya conecta personas que buscan trabajo rápido con quienes lo ofrecen. No somos una agencia, somos un puente digital.</p>
                    </div>
                </section>

                <!-- Sección 2: Puente de Conexión -->
                <section class="terms-section" id="conexion">
                    <div class="section-head">
                        <span class="section-number">2</span>
                        <h2>El Puente de Conexión</h2>
                    </div>
                    <p>Chamba Ya es únicamente una herramienta que facilita el contacto entre quienes buscan y ofrecen trabajo rápido. <strong>No somos una agencia de empleo</strong> ni participamos en la selección, supervisión o contratación del personal.</p>
                    <p>Nuestra misión es ser el puente digital que permite que las personas se encuentren de manera rápida y eficiente para cubrir necesidades laborales del día a día.</p>
                </section>

                <!-- Sección 3: Trato y Pagos -->
                <section class="terms-section" id="pagos">
                    <div class="section-head">
                        <span class="section-number">3</span>
                        <h2>Trato y Pagos Directos</h2>
                    </div>
                    <p>Todo acuerdo sobre el precio, el horario y la forma de pago (efectivo, Yape, Plin, etc.) se realiza <strong>exclusivamente entre el empleador y el trabajador</strong>.</p>
                    <p>Chamba Ya no recibe comisiones por los trabajos ni se hace responsable por incumplimientos de pago o servicios mal realizados.</p>
                    <div class="highlight-box">
                        <p><i class="fa-solid fa-circle-info"></i>&nbsp; Recomendamos siempre acordar los detalles del trabajo (precio, horario, duración) antes de empezar cualquier tarea.</p>
                    </div>
                </section>

                <!-- Sección 4: Compromiso del Usuario -->
                <section class="terms-section" id="compromiso">
                    <div class="section-head">
                        <span class="section-number">4</span>
                        <h2>Compromiso del Usuario</h2>
                    </div>
                    <p>Te comprometes a ser mayor de edad, brindar información real y publicar contenido lícito. Queda <strong>prohibido</strong> el uso de la plataforma para actividades ilegales, engañosas o spam.</p>
                    <p>Mantener la veracidad de tu perfil es fundamental para que la comunidad funcione correctamente y todos puedan confiar entre sí.</p>
                </section>

                <!-- Sección 5: Privacidad de Datos -->
                <section class="terms-section" id="privacidad">
                    <div class="section-head">
                        <span class="section-number">5</span>
                        <h2>Privacidad y Datos</h2>
                    </div>
                    <p>Para que el sistema funcione, compartiremos tu nombre y teléfono con otros usuarios interesados en tu anuncio. <strong>No vendemos tus datos a terceros</strong>; tu información se usa solo para fines de contacto laboral dentro de la web.</p>
                    <p>Tu privacidad es importante para nosotros. Solo compartimos la información mínima necesaria para facilitar la conexión laboral entre las partes.</p>
                </section>

                <!-- Sección 6: Reportes -->
                <section class="terms-section" id="reportes">
                    <div class="section-head">
                        <span class="section-number">6</span>
                        <h2>Sistema de Confianza (Reportes)</h2>
                    </div>
                    <p>Valoramos la puntualidad y la honestidad. Si un usuario es reportado por "plantones" o mala conducta, Chamba Ya investigará el caso.</p>
                    <div class="highlight-box warning">
                        <p><i class="fa-solid fa-triangle-exclamation"></i>&nbsp; Al acumular 3 reportes negativos, el número de teléfono será bloqueado de forma permanente.</p>
                    </div>
                </section>

                <!-- Sección 7: Seguridad -->
                <section class="terms-section" id="seguridad">
                    <div class="section-head">
                        <span class="section-number">7</span>
                        <h2>Recomendaciones de Seguridad</h2>
                    </div>
                    <p>Al ser un trato entre particulares, sugerimos siempre:</p>
                    <ul class="check-list">
                        <li><i class="fa-solid fa-circle-check"></i> Solicitar el <strong>DNI</strong> de la otra parte para verificar identidad.</li>
                        <li><i class="fa-solid fa-circle-check"></i> Encontrarse en <strong>lugares públicos</strong> para el primer contacto.</li>
                        <li><i class="fa-solid fa-circle-check"></i> Dejar claros los <strong>detalles de la tarea</strong> por escrito antes de empezar.</li>
                    </ul>
                    <p>Tu seguridad es tu responsabilidad. Tomar estas precauciones básicas te ayudará a tener experiencias positivas en Chamba Ya.</p>
                </section>

            </div>
        </main>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/js/functions_terminos.js"></script>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
