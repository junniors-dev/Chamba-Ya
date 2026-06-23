<?php
// views/auth/detalle_servicio.php
require_once __DIR__ . '/../../assets/css/style_detalleServicio.php';
require_once __DIR__ . '/../templates/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Servicio - <?= htmlspecialchars($anuncio['nombres']) ?></title>
    
</head>
<body>

    <div class="container-servicio">
        <div class="wrapper-layout-servicio">
            
            <main class="col-perfil-principal">
                <div class="encabezado-usuario">
                    <?php $foto = !empty($anuncio['fotoPerfil']) ? $anuncio['fotoPerfil'] : 'default.png'; ?>
                    <img src="<?= BASE_URL ?>assets/uploads/img_perfiles/<?= $foto ?>" 
                        alt="Foto de Perfil" class="foto-perfil-avatar"
                        onerror="this.src='<?= BASE_URL ?>assets/uploads/img_perfiles/default.png';">
                    
                    <div class="info-usuario-titulo">
                        <h1><?= htmlspecialchars($anuncio['nombres'] . ' ' . $anuncio['apellidos']) ?></h1>
                        <span class="rol-tag">Ofrece Servicio</span>
                    </div>
                </div>

                <div class="seccion-bloque-info">
                    <h3>Sobre mí</h3>
                    <p><?= htmlspecialchars($anuncio['descripcionPerfil'] ?? 'Este usuario no ha añadido una descripción a su perfil todavía.') ?></p>
                </div>

                <div class="seccion-bloque-info">
                    <h3>Descripción del Servicio solicitado</h3>
                    <p><?= htmlspecialchars($anuncio['descripcion']) ?></p>
                </div>

                <div class="seccion-bloque-info">
                    <h3>Categorías</h3>
                    <div class="contenedor-badges">
                        <?php if (!empty($anuncio['categorias_nombres'])): ?>
                            <?php foreach (explode(', ', $anuncio['categorias_nombres']) as $cat): ?>
                                <span class="badge-item categoria"><?= htmlspecialchars($cat) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="badge-item categoria">General</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="seccion-bloque-info">
                    <h3>Habilidades</h3>
                    <div class="contenedor-badges">
                        <span class="badge-item">Puntualidad</span>
                        <span class="badge-item">Eficiencia</span>
                        <span class="badge-item">Trabajo Garantizado</span>
                    </div>
                </div>
            </main>

            <aside class="col-sidebar-datos">
                <div class="precio-box">
                    Precio Estimado
                    <strong>S/. <?= number_format($anuncio['pagoReferencia'], 2) ?></strong>
                </div>

                <div class="item-contacto-sidebar">
                    <span>Ubicación</span>
                    <p><?= htmlspecialchars($anuncio['ubicacion_completa']) ?></p>
                </div>

                <div class="item-contacto-sidebar">
                    <span>Detalle:</span>
                    <p><?= htmlspecialchars($anuncio['direccionEspecificas'] ?? $anuncio['direccionEspecifica'] ?? 'No especificada') ?></p>
                </div>

                <div class="item-contacto-sidebar">
                    <span>Teléfono</span>
                    <p><?= htmlspecialchars($anuncio['telefono'] ?? 'No especificado') ?></p>
                </div>

                <div class="item-contacto-sidebar">
                    <span>Correo Electrónico</span>
                    <p style="word-break: break-all;"><?= htmlspecialchars($anuncio['correo']) ?></p>
                </div>

                <div class="item-contacto-sidebar">
                    <span>Modalidad</span>
                    <p><?= htmlspecialchars($anuncio['modalidad']) ?></p>
                </div>

                <button class="btn-solicitar-servicio" type="button">Contratar Servicio</button>
            </aside>

        </div>

        <section class="seccion-otros-servicios">
            <h2>Servicios que ofrezco</h2>
            <div class="grid-otros-servicios">
                <?php if (!empty($otrosServicios)): ?>
                    <?php foreach ($otrosServicios as $otro): ?>
                        <div class="tarjeta-mini-servicio">
                            <div>
                                <h4><?= htmlspecialchars($otro['titulo']) ?></h4>
                                <p><?= htmlspecialchars($otro['descripcion']) ?></p>
                            </div>
                            <div class="footer-mini-tarjeta">
                                <span style="font-weight: bold; color: #d8a500;">S/. <?= number_format($otro['pagoReferencia'], 2) ?></span>
                                <a href="index.php?action=detalle-anuncio&id=<?= $otro['idAnuncio'] ?>" class="btn-ver-detalle-mini">
                                    Ver Detalle
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #64748b; font-style: italic;">El ofertante no cuenta con otros servicios activos en este momento.</p>
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
                                    $puntaje = intval($testimonio['getPuntaje'] ?? $testimonio['puntaje']); 
                                    for ($i = 1; $i <= 5; $i++): 
                                        if ($i <= $puntaje): ?>
                                            <i class="fa-solid fa-star" style="color: #ffcc00;"></i>
                                        <?php else: ?>
                                            <i class="fa-regular fa-star" style="color: #ccc;"></i>
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
                <p class="sin-testimonios">Este perfil de servicio aún no cuenta con calificaciones o testimonios.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php require_once __DIR__ . '/../../views/templates/footer.php'; ?>
</body>
</html>