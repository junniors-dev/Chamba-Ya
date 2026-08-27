<?php
    // views/user/perfil_publico.php
    require_once __DIR__ . '/../../assets/css/style_detalleServicio.php';
    require_once __DIR__ . '/../../assets/css/style_portafolio.php';
    require_once __DIR__ . '/../templates/head.php';
    require_once __DIR__ . '/../templates/header.php';
?>
<body>

    <?php if (!empty($_GET['estado'])):
        $msgs = [
            'trab_guardado' => ['Trabajador agregado a tus guardados.', '#16a34a'],
            'trab_quitado'  => ['Trabajador quitado de tus guardados.', '#64748b'],
            'trab_propio'   => ['No puedes guardarte a ti mismo.',      '#d97706'],
            'error'         => ['Ocurrió un error. Inténtalo de nuevo.', '#dc2626'],
        ];
        $b = $msgs[$_GET['estado']] ?? null;
        if ($b): ?>
            <div style="max-width:1100px;margin:15px auto;padding:12px 18px;border-radius:8px;color:#fff;background:<?= $b[1] ?>;font-weight:600;text-align:center;">
                <?= htmlspecialchars($b[0]) ?>
            </div>
    <?php endif; endif; ?>

    <div class="container-servicio">
        <a href="<?= BASE_URL ?>index.php?action=buscar-trabajo&tipo=servicio" class="btn-volver"><i class="fa-solid fa-arrow-left"></i> Volver a los servicios</a>

        <div class="wrapper-layout-servicio">

            <main class="col-perfil-principal">
                <div class="encabezado-usuario">
                    <?php $foto = !empty($usuario['fotoPerfil']) ? $usuario['fotoPerfil'] : 'default.png'; ?>
                    <img src="<?= $base_path ?>assets/uploads/img_perfiles/<?= htmlspecialchars($foto) ?>"
                        alt="Foto de Perfil" class="foto-perfil-avatar"
                        onerror="this.src='<?= $base_path ?>assets/uploads/img_perfiles/default.png';">

                    <div class="info-usuario-titulo">
                        <h1><?= htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']) ?></h1>
                        <span class="rol-tag">Perfil de Trabajador</span>
                    </div>
                </div>

                <div class="seccion-bloque-info">
                    <h3>Sobre mí</h3>
                    <p><?= htmlspecialchars($usuario['descripcionPerfil'] ?? 'Este usuario no ha añadido una descripción a su perfil todavía.') ?></p>
                </div>

                <div class="seccion-bloque-info">
                    <h3>Habilidades</h3>
                    <div class="contenedor-badges">
                        <?php if (!empty($habilidades)): ?>
                            <?php foreach ($habilidades as $hab): ?>
                                <span class="badge-item"><?= htmlspecialchars($hab) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="badge-item-noes">Aún no especificó habilidades</span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($portafolio)): ?>
                    <!-- Trabajos anteriores: solo se muestra si hay alguno, para no
                         dejar un hueco vacío en los perfiles que aún no lo usan. -->
                    <div class="seccion-bloque-info">
                        <h3>Trabajos anteriores</h3>
                        <div class="portafolio-grid">
                            <?php foreach ($portafolio as $trabajo): ?>
                                <div class="portafolio-item">
                                    <?php if (!empty($trabajo['imagen'])): ?>
                                        <img src="<?= BASE_URL ?>assets/uploads/portafolio/<?= htmlspecialchars($trabajo['imagen']) ?>"
                                             alt="<?= htmlspecialchars($trabajo['titulo']) ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="portafolio-sinimg"><i class="fa-regular fa-image"></i></div>
                                    <?php endif; ?>
                                    <div class="portafolio-cuerpo">
                                        <h4><?= htmlspecialchars($trabajo['titulo']) ?></h4>
                                        <?php if (!empty($trabajo['descripcion'])): ?>
                                            <p><?= nl2br(htmlspecialchars($trabajo['descripcion'])) ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($trabajo['categoria'])): ?>
                                            <span class="portafolio-cat"><?= htmlspecialchars($trabajo['categoria']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </main>

            <aside class="col-sidebar-datos">
                <div class="item-contacto-sidebar">
                    <span>Ubicación</span>
                    <p><?= htmlspecialchars($ubicacionText) ?></p>
                </div>

                <div class="item-contacto-sidebar">
                    <span>Calificación</span>
                    <div class="estrellas">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $puntaje): ?>
                                <i class="fa-solid fa-star" style="color:#ffcc00;"></i>
                            <?php else: ?>
                                <i class="fa-regular fa-star" style="color:#ccc;"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span>(<?= number_format($puntaje, 1) ?>/5)</span>
                    </div>
                </div>

                <?php if (!empty($usuario['telefono'])): ?>
                    <?php $wa = linkWhatsApp($usuario['telefono'], 'Hola ' . $usuario['nombres'] . ', vi tu perfil en Chamba Ya y quiero contactarte.'); ?>
                    <?php if ($wa): ?>
                        <a href="<?= htmlspecialchars($wa) ?>" target="_blank" rel="noopener" class="btn-contacto-whatsapp">
                            <i class="fa-brands fa-whatsapp"></i> Contactar por WhatsApp
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!$esUnoMismo): ?>
                    <?php if ($idUsuarioActivo > 0): ?>
                        <button type="button" class="button_guardar_user" onclick="toggleFavoritoTrabajador(<?= (int) $idUsuario ?>, this)">
                            <i class="fa-regular fa-heart"></i> <?= $esTrabajadorFavorito ? 'Quitar de mis trabajadores' : 'Guardar trabajador' ?>
                        </button>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>views/auth/login.php" class="button_guardar_user" style="display:block;text-align:center;text-decoration:none;">
                            <i class="fa-regular fa-heart"></i> Inicia sesión para guardar
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php
                    $urlPerfil = BASE_URL . 'controllers/PerfilController.php?id=' . (int) $idUsuario;
                    $mensajeCompartirPerfil = 'Mira el perfil de ' . $usuario['nombres'] . ' en Chamba Ya: ' . $urlPerfil;
                ?>
                <a class="btn-compartir-whatsapp" href="<?= htmlspecialchars(linkCompartirWhatsApp($mensajeCompartirPerfil)) ?>" target="_blank" rel="noopener">
                    <i class="fa-brands fa-whatsapp"></i> Compartir perfil
                </a>
            </aside>
        </div>

        <section class="seccion-otros-servicios">
            <h2>Servicios que ofrece</h2>
            <div class="grid-otros-servicios">
                <?php if (!empty($servicios)): ?>
                    <?php foreach ($servicios as $s): ?>
                        <div class="tarjeta-mini-servicio">
                            <div>
                                <h4><?= htmlspecialchars($s['titulo']) ?></h4>
                                <p><?= htmlspecialchars($s['descripcion']) ?></p>
                            </div>
                            <div class="footer-mini-tarjeta">
                                <span class="pagoReferencia"><?= htmlspecialchars(formatearPago($s['pagoReferencia'])) ?></span>
                                <a href="<?= BASE_URL ?>index.php?action=detalle-anuncio&id=<?= (int) $s['idAnuncio'] ?>&tipo=servicio" class="btn-ver-detalle-mini">
                                    Ver Detalle
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Este usuario no tiene servicios activos en este momento.</p>
                <?php endif; ?>
            </div>
        </section>

        <div class="seccion-testimonios">
            <h2>Opiniones de los usuarios</h2>
            <?php if (!empty($testimonios)): ?>
                <div class="lista-testimonios">
                    <?php foreach ($testimonios as $testimonio): ?>
                        <div class="tarjeta-testimonio">
                            <div class="testimonio-header">
                                <div class="usuario-info">
                                    <strong><?= htmlspecialchars($testimonio['nombres'] . ' ' . $testimonio['apellidos']) ?></strong>
                                    <span class="testimonio-fecha"><?= date('d/m/Y', strtotime($testimonio['fecha'])) ?></span>
                                </div>
                                <div class="estrellas">
                                    <?php
                                        $ptj = intval($testimonio['puntaje']);
                                        for ($i = 1; $i <= 5; $i++):
                                            if ($i <= $ptj): ?>
                                                <i class="fa-solid fa-star" style="color:#ffcc00;"></i>
                                            <?php else: ?>
                                                <i class="fa-regular fa-star" style="color:#ccc;"></i>
                                            <?php endif;
                                        endfor; ?>
                                </div>
                            </div>
                            <div class="testimonio-cuerpo">
                                <p><?= htmlspecialchars($testimonio['comentario']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="sin-testimonios">Este perfil aún no cuenta con calificaciones o testimonios.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once __DIR__ . '/../templates/footer.php'; ?>
    <script>const basePath = "<?= BASE_URL ?>";</script>
    <script src="<?= BASE_URL ?>assets/js/favoritos_ajax.js"></script>
</body>
</html>
