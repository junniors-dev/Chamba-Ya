<?php require_once __DIR__ . '/../../core/config/config.php'; ?>
<?php require_once __DIR__ . '/../../assets/css/style_footer.php'; ?>

<footer class="footer">
    <div class="footer_container">
        <div class="footer_row">
            <div class="footer_links">
                <h4>Nosotros</h4>
                <div class="footer_content">
                    <ul>
                        <li><a href="<?= BASE_URL ?>views/footer-views/quienes_somos.php">Quienes Somos</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer_links">
                <h4>Información Legal</h4>
                <div class="footer_content">
                    <ul>
                        <li><a href="<?= BASE_URL ?>views/footer-views/terminos_y_condiciones.php">Terminos y Condiciones</a></li>
                        <li><a href="<?= BASE_URL ?>views/footer-views/preguntas_frecuentes.php">Preguntas frecuentes</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer_links" id="footer-redes">
                <h4>Siguenos</h4>
                <div class="footer_content">
                    <div class="social_links">
                        <a href="https://www.facebook.com/share/1Bo4gejadn/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/clinicacentenariodelnorte?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="><i class="fab fa-instagram"></i></a>
                        <a href="https://www.tiktok.com/@centenario_delnorte?_r=1&_t=ZS-91kjkdxP0vo"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
