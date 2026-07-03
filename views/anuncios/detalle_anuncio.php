<?php
    // views/anuncios/detalle_anuncio.php
    $pageTitle = $anuncio['titulo'] . ' - Chamba Ya';
    require_once __DIR__ . '/../../assets/css/style_detalleTrabajo.php';
    require_once __DIR__ . '/../templates/head.php';
    require_once __DIR__ . '/../templates/header.php';
?>

<body>

    <?php require_once __DIR__ . '/_banner_estado.php'; ?>

    <div class="container-detalle">
        <a href="<?= BASE_URL ?>index.php?action=buscar-trabajo&tipo=trabajo" class="btn-volver"><i class="fa-solid fa-arrow-left"></i> Volver a las ofertas</a>
        <!-- Rótulo de la Sección -->
        <div class="titulo-seccion-detalle">Detalles del Trabajo</div>
        
        <!-- Título Dinámico del Anuncio -->
        <h1 class="main-titulo-anuncio"><?= htmlspecialchars($anuncio['titulo']) ?></h1>
        
        <div class="layout-flex-detalle">
            
            <!-- SECCIÓN IZQUIERDA: CONTENIDO DEL ANUNCIO -->
            <div class="bloque-izquierdo">
                
                <!-- Fila de Ubicación y Sueldo -->
                <div class="fila-encabezados-mini">
                    <div class="caja-info-mini">
                        <span class="label">Departamento - Provincia - Distrito</span>
                        <?= htmlspecialchars($anuncio['ubicacion_completa']) ?>
                    </div>
                    <div class="caja-info-mini">
                        <span class="label">Pago Referencial:</span><strong><?= htmlspecialchars(formatearPago($anuncio['pagoReferencia'])) ?><?= ((float)($anuncio['pagoReferencia'] ?? 0) > 0) ? ' / hora' : '' ?></strong>
                    </div>
                </div>
                
                <!-- Cuerpo de la Tarjeta -->
                <div class="tarjeta-principal-cuerpo">
                    <div class="subtitulo-cuerpo">Descripción:</div>
                    <p class="descripcion-texto"><?= htmlspecialchars($anuncio['descripcion']) ?></p>
                    
                    <hr class="divisor-linea">
                    
                    <!-- Datos Técnicos en Grid -->
                    <div class="grid-detalles-secundarios">
                        <div class="item-secundario">
                            <span class="label">Modalidad:</span>
                            <span class="valor"><?= htmlspecialchars($anuncio['modalidad']) ?></span>
                        </div>
                        <div class="item-secundario">
                            <span class="label">Dirección del trabajo:</span>
                            <span class="valor"><?= htmlspecialchars($anuncio['direccionEspecificas'] ?? $anuncio['direccionEspecifica'] ?? 'Se pone la direccion especifica') ?></span>
                        </div>
                    </div>
                    
                    <hr class="divisor-linea">
                    
                    <div class="grid-detalles-secundarios">
                        <div class="item-secundario">
                            <span class="label">Estado del trabajo:</span>
                            <span class="valor"><?= htmlspecialchars($anuncio['estado']) ?></span>
                        </div>
                    </div>
                    
                    <!-- Bloque de Categorías Embebidas -->
                    <div class="contenedor-tags-categorias">
                        <span class="label">Categorías:</span>
                        <div class="lista-tags">
                            <?php if (!empty($anuncio['categorias_nombres'])): ?>
                                <?php foreach (explode(', ', $anuncio['categorias_nombres']) as $cat_nom): ?>
                                    <span class="tag-categoria"><?= htmlspecialchars($cat_nom) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="tag-categoria" style="background:#f2f4f4; color:#7f8c8d; border:1px solid #d5dbdb;">General</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Botonera Inferior -->
                    <div class="bloque-acciones">
                        <form action="<?= BASE_URL ?>controllers/PostulacionController.php" method="POST" style="display:inline;">
                            <input type="hidden" name="idAnuncio" value="<?= (int) $anuncio['idAnuncio'] ?>">
                            <button class="btn-postular" type="submit">Contactar / Postularse</button>
                        </form>
                        <?php $esFavorito = $esFavorito ?? false; ?>
                        <form action="<?= BASE_URL ?>controllers/AnuncioGuardadoController.php" method="POST" style="display:inline;">
                            <input type="hidden" name="idAnuncio" value="<?= (int) $anuncio['idAnuncio'] ?>">
                            <button class="btn-favorito" type="submit">
                                <?= $esFavorito ? 'Quitar de Favoritos' : 'Añadir a Favoritos' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN DERECHA: TARJETA DE PERFIL DEL EMISOR -->
            <aside class="bloque-derecho-perfil">
                <div class="titulo-perfil-card">Publicado por:</div>
                <div class="nombre-usuario-card">
                    <?= htmlspecialchars($anuncio['nombres'] . ' ' . $anuncio['apellidos']) ?>
                </div>
                
                <hr class="divisor-linea">
                
                <div class="datos-contacto-card">
                    <strong>Teléfono:</strong> <?= htmlspecialchars($anuncio['telefono'] ?? 'No especificado') ?>
                </div>
                <?php $wa = linkWhatsApp($anuncio['telefono'] ?? '', 'Hola, vi tu anuncio "' . ($anuncio['titulo'] ?? '') . '" en Chamba Ya y me interesa.'); ?>
                <?php if ($wa): ?>
                    <a href="<?= htmlspecialchars($wa) ?>" target="_blank" rel="noopener"
                       style="display:inline-flex;align-items:center;gap:8px;margin:10px 0;padding:10px 16px;background:#25D366;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;">
                        <i class="fa-brands fa-whatsapp"></i> Contactar por WhatsApp
                    </a>
                <?php endif; ?>
                <div class="datos-contacto-card">
                    <strong>Correo de contacto:</strong> <?= htmlspecialchars($anuncio['correo']) ?>
                </div>
                
                <div class="datos-contacto-card">
                    <strong>Calificación del usuario:</strong>

                    <div class="estrellas">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <?php if($i <= $puntaje): ?>
                                <i class="fa-solid fa-star" style="color:#ffcc00;"></i>
                            <?php else: ?>
                                <i class="fa-regular fa-star" style="color:#ccc;"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span>(<?= number_format($puntaje,1) ?>/5)</span>
                    </div>
                </div>

                <?php 
                    $foto = !empty($anuncio['fotoPerfil']) ? $anuncio['fotoPerfil'] : 'default.png'; 
                ?>
                <div style="margin-top: 15px;">
                    <span class="label">(Foto de Perfil)</span>
                    <img src="<?= $base_path ?>assets/uploads/img_perfiles/<?= $foto ?>" 
                        alt="Foto de Perfil del Reclutador" 
                        class="avatar-perfil-img"
                        onerror="this.src='<?= $base_path ?>assets/uploads/img_perfiles/default.png';">
                </div>

                <?php $tipoReporte = 'trabajo'; require __DIR__ . '/_form_reporte.php'; ?>
            </aside>
        </div>
    </div>
    <?php require_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
