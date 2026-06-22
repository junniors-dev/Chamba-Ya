<?php
    session_start();

    require_once __DIR__ . '/../../core/config/config.php';
    require_once __DIR__ . '/../../assets/css/style.php';
    require_once __DIR__ . '/../../assets/css/styles.php';
    require_once __DIR__ . '/../../assets/css/style_faq.php';   
    require_once __DIR__ . '/../templates/head.php';
    require_once __DIR__ . '/../templates/header.php';
?>

<div class="faq-page">
    <!-- Hero Section -->
    <section class="faq-hero">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>index.php">Inicio</a> &nbsp;/&nbsp; Preguntas frecuentes
        </div>
        <h1>¿En qué podemos ayudarte?</h1>
        <p>Encuentra respuestas rápidas a las preguntas más comunes sobre Chamba Ya.</p>
        <div class="faq-search-wrapper">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="faqSearch" placeholder="Escribe tu pregunta aquí...">
        </div>
    </section>

    <!-- Category Tabs -->
    <div class="faq-tabs">
        <button class="faq-tab active" data-tab="postulantes">
            <span class="tab-icon"><i class="fa-solid fa-user-tie"></i></span>
            <span class="tab-text">
                Busco Chamba
                <small>Postulantes</small>
            </span>
        </button>
        <button class="faq-tab" data-tab="empleadores">
            <span class="tab-icon"><i class="fa-solid fa-briefcase"></i></span>
            <span class="tab-text">
                Ofrezco Chamba
                <small>Empleadores</small>
            </span>
        </button>
    </div>

    <!-- FAQ Content -->
    <div class="faq-main">

        <!-- Postulantes Section -->
        <div class="faq-column-section active" id="postulantes">

            <div class="faq-item active">
                <div class="faq-question">
                    ¿Es gratis postular a los trabajos?
                    <span class="q-icon"><i class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">¡Sí! Postular a cualquier vacante en Chamba Ya es totalmente gratis para todos los usuarios. No cobramos comisiones ni mensualidades. Nuestra misión es que el acceso al trabajo sea democrático.</div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    ¿Necesito tener un CV profesional para usar la página?
                    <span class="q-icon"><i class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">No, no necesitas un CV formal. Chamba Ya está diseñado para conectar trabajadores de oficios rápidos directamente. Solo completa tu perfil con tus datos básicos, una foto clara y una descripción de tus habilidades.</div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    ¿Qué hago si el empleador no me responde?
                    <span class="q-icon"><i class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Te recomendamos tener paciencia. Si no hay respuesta después de unos días, te sugerimos seguir postulando a otras ofertas disponibles cerca de ti. ¡Hay muchas oportunidades esperándote!</div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    ¿Puedo postular a varios trabajos a la vez?
                    <span class="q-icon"><i class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Sí, puedes postular a tantas ofertas como consideres que puedes cumplir. Pero recuerda siempre ser responsable con los compromisos que asumas para mantener tu reputación positiva.</div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    ¿Cómo puedo hacer que mi perfil destaque?
                    <span class="q-icon"><i class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Sube una foto clara y profesional, escribe una descripción detallada de tus habilidades reales y asegúrate de recibir buenas valoraciones. ¡Un historial sin reportes te dará prioridad ante los empleadores!</div>
                </div>
            </div>

        </div>

        <!-- Empleadores Section -->
        <div class="faq-column-section" id="empleadores">

            <div class="faq-item">
                <div class="faq-question">
                    ¿Cuánto tiempo tarda en aparecer mi anuncio?
                    <span class="q-icon"><i class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Una vez que completas el formulario y le das a "Publicar", tu anuncio es visible de inmediato para miles de personas en tu zona. ¡Sin esperas, sin aprobaciones!</div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    ¿Cómo contacto a los interesados?
                    <span class="q-icon"><i class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Los postulantes tendrán acceso a tu número de contacto para escribirte directamente (por ejemplo, vía WhatsApp). Tú también podrás ver los perfiles de quienes postulan a tu anuncio.</div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    ¿Puedo editar o borrar mi anuncio una vez publicado?
                    <span class="q-icon"><i class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Sí, en la sección "Anuncios creados" de tu perfil puedes marcar tu anuncio como finalizado o eliminarlo por completo para dejar de recibir contactos.</div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    ¿Mi anuncio vence en algún momento?
                    <span class="q-icon"><i class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Los anuncios tienen un tiempo de visibilidad estándar. Si no lograste encontrar a alguien, podrás volver a republicarlo fácilmente desde tu panel de control.</div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    ¿Cómo hago para que mi anuncio aparezca primero?
                    <span class="q-icon"><i class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Próximamente implementaremos opciones premium para destacar anuncios. Por ahora, todos se ordenan cronológicamente: los más recientes aparecen primero.</div>
                </div>
            </div>

        </div>

        <!-- No results message -->
        <div class="faq-no-results">
            <i class="fa-regular fa-face-frown"></i>
            <p>No encontramos resultados para tu búsqueda.</p>
        </div>

    </div>
</div>

<script src="<?= BASE_URL ?>assets/js/functions_faq.js"></script>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
