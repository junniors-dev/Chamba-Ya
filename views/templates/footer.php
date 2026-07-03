<?php require_once __DIR__ . '/../../core/config/config.php'; ?>
<?php require_once __DIR__ . '/../../assets/css/style_footer.php'; ?>

<footer class="footer">
    <div class="footer_container">
        <div class="footer_top">

            <!-- Marca -->
            <div class="footer_brand">
                <a class="footer_logo" href="<?= BASE_URL ?>index.php" aria-label="Chamba Ya - inicio">
                    <img src="<?= BASE_URL ?>assets/img/logo-chamba-ya.png" alt="Chamba Ya" width="170">
                </a>
                <p class="footer_tagline">Tu chamba al toque, sin CV y con contacto directo. Conecta trabajadores y clientes cerca de ti.</p>
                <a class="footer_cta" href="<?= BASE_URL ?>views/user/mis_anuncios.php">
                    <i class="fa-solid fa-plus"></i> Publica tu chamba
                </a>

                <div class="footer_social">
                    <span class="social_label">Síguenos</span>
                    <div class="social_links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>

            <!-- Columnas de enlaces (agrupadas) -->
            <div class="footer_cols">
                <div class="footer_links">
                    <h4>Explora</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>index.php?action=buscar-trabajo&tipo=trabajo">Ofertas de trabajo</a></li>
                        <li><a href="<?= BASE_URL ?>index.php?action=buscar-trabajo&tipo=servicio">Ofertas de servicio</a></li>
                        <li><a href="<?= BASE_URL ?>index.php">Categorías</a></li>
                    </ul>
                </div>

                <div class="footer_links">
                    <h4>Empresa</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>views/footer-views/quienes_somos.php">Quiénes somos</a></li>
                        <li><a href="<?= BASE_URL ?>index.php">Cómo funciona</a></li>
                    </ul>
                </div>

                <div class="footer_links">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>views/footer-views/terminos_y_condiciones.php">Términos y condiciones</a></li>
                        <li><a href="<?= BASE_URL ?>views/footer-views/politicas_privacidad.php">Políticas de privacidad</a></li>
                        <li><a href="<?= BASE_URL ?>views/footer-views/preguntas_frecuentes.php">Preguntas frecuentes</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Barra inferior -->
        <div class="footer_bottom">
            <span>&copy; <?= date('Y') ?> Chamba Ya. Todos los derechos reservados.</span>
            <span class="footer_made"><i class="fa-solid fa-location-dot"></i> Hecho en Perú</span>
        </div>
    </div>
</footer>

<!-- Banner de consentimiento de cookies (global) -->
<div id="cookie-banner" class="cookie-banner" style="display:none;">
    <p class="cookie-banner__text">
        🍪 Usamos cookies para recordar tu sesión y mejorar tu experiencia.
        <a href="<?= BASE_URL ?>views/footer-views/politicas_privacidad.php">Política de privacidad</a>
    </p>
    <button type="button" class="cookie-banner__btn" onclick="aceptarCookies()">Aceptar</button>
</div>

<style>
    .cookie-banner{
        position: fixed;
        left: 16px;
        right: 16px;
        bottom: 16px;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        max-width: 900px;
        margin: 0 auto;
        background: #15231a;
        color: #fff;
        padding: 16px 20px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,.25);
        opacity: 0;
        transform: translateY(20px);
        transition: opacity .25s ease, transform .25s ease;
        font-family: 'Inter', system-ui, Arial, sans-serif;
    }
    .cookie-banner--visible{ opacity: 1; transform: translateY(0); }
    .cookie-banner__text{ margin: 0; font-size: 14px; line-height: 1.5; flex: 1; min-width: 220px; }
    .cookie-banner__text a{ color: #FFC700; text-decoration: underline; }
    .cookie-banner__btn{
        background: #25C25E;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap;
        transition: background .15s ease;
    }
    .cookie-banner__btn:hover{ background: #18994a; }
    @media (max-width: 560px){
        .cookie-banner{ flex-direction: column; align-items: stretch; text-align: center; }
        .cookie-banner__btn{ width: 100%; }
    }
</style>
<script src="<?= BASE_URL ?>assets/js/cookie_consent.js"></script>
