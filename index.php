<?php
    session_start();

    require_once 'assets/css/style.php';
    require_once 'assets/css/styles.php';
    require_once 'templates/head.php';
    require_once 'templates/header.php';
?>

    <body>
        <div class="container_halfs">
            <div class="left_half">
                <h1>¿Buscas trabajo rápido?</h1>
                <p>Encuentra ofertas cerca de ti y postula al toque. Sin CV, contacto directo</p>
                <img src="../assets/img/imagen_left.png" alt="Imagen Left">
                <button class="btn_left_first">[Ver ofertas de Trabajo]</button>
                <button class="btn_left_second">[Publicar mi perfil de servicio]</button>
            </div>
            <div class="right_half">
                <h1>¿Necesitas ayuda ahora?</h1>
                <p>Encuentra a trabajadores que te ayuden o publica tu necesidad. Gasfiteres, electricistas, limpieza, jardinero y más.</p>
                <img src="../assets/img/imagen_right.png" alt="Imagen Right">
                <button class="btn_right_first">[Buscar trabajadores]</button>
                <button class="btn_right_second">[Publicar oferta de trabajo]</button>
            </div>
        </div>
        <div class="categories">
            <h2>Categorías:</h2>
            <div class="categories_carousel">
                <button class="carousel_btn prev" onclick="scrollCarousel(-1)">&#10094;</button>
                <div class="carousel_wrapper" id="carouselWrapper">
                    <a href="" class="categories_card">
                        <img src="../assets/img/hogar_mantenimiento.png" alt="hogar y mantenimiento">
                        <h3>Hogar y Mantenimiento</h3>
                    </a>
                    <a href="" class="categories_card">
                        <img src="../assets/img/jardineria.png" alt="jardinería">
                        <h3>Jardinería</h3>
                    </a>
                    <a href="" class="categories_card">
                        <img src="../assets/img/construccion.png" alt="construcción">
                        <h3>Construcción</h3>
                    </a>
                    <a href="" class="categories_card">
                        <img src="../assets/img/limpieza.png" alt="limpieza">
                        <h3>Limpieza</h3>
                    </a>
                    <a href="" class="categories_card">
                        <img src="../assets/img/mascotas.png" alt="mascotas">
                        <h3>Mascotas</h3>
                    </a>
                    <a href="" class="categories_card">
                        <img src="../assets/img/cocina_eventos.png" alt="cocina y eventos">
                        <h3>Cocina y Eventos</h3>
                    </a>
                    <a href="" class="categories_card">
                        <img src="../assets/img/mudanzas_carga.png" alt="mudanzas y carga">
                        <h3>Mudanzas y Carga</h3>
                    </a>
                    <a href="" class="categories_card">
                        <img src="../assets/img/servicios_digitales.png" alt="servicios digitales">
                        <h3>Servicios Digitales</h3>
                    </a>
                    <a href="" class="categories_card">
                        <img src="../assets/img/servicios_varios.png" alt="servicios varios">
                        <h3>Servicios Varios</h3>
                    </a>
                    <a href="" class="categories_card">
                        <img src="../assets/img/transporte_delivery.png" alt="transporte y delivery">
                        <h3>Transporte y Delivery</h3>
                    </a>
                </div>
                <button class="carousel_btn next" onclick="scrollCarousel(1)">&#10095;</button>
            </div>
            
        </div>
        </div>
        <div class="why_chamba_ya">
            <h1>¿Por qué usar "Chamba Ya"?</h1>
            <div class="why_cards_container">
                <div class="why_card">
                    <img src="../assets/img/no_cv.png" alt="SIN CV">
                    <h2>SIN CV</h2>
                    <p>No necesitas tener curriculum</p>
                </div>
                <div class="why_card">
                    <img src="../assets/img/contacto_directo.png" alt="CONTACTO DIRECTO">
                    <h2>CONTACTO DIRECTO</h2>
                    <p>Contacta directamente con los creadores del puesto</p>
                </div>
                <div class="why_card">
                    <img src="../assets/img/trabajo_rapido.png" alt="TRABAJOS RÁPIDOS">
                    <h2>TRABAJOS RÁPIDOS</h2>
                    <p>Consigue un trabajo rápido según tus habilidades</p>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="footer_container">
                <div class="footer_row">
                    <div class="footer_links" id="footer_nosotros">
                        <h4>Nosotros</h4>
                            <div class="footer_content">
                                <ul>
                                    <li><a href="">Quienes Somos</a></li>
                                </ul>
                            </div>
                        </div>
                <div class="footer_links" id="footer_nosotros">
                    <h4>Información Legal</h4>
                    <div class="footer_content">
                            <ul>
                                <li><a href="">Politicas de privacidad</a></li>
                                <li><a href="">Terminos y Condiciones</a></li>
                                <li><a href="">Preguntas frecuentes</a></li>
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
    </body>
    <script src="assets/js/index.js"></script>
    </html>