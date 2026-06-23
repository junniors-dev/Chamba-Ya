<?php
// views/auth/detalle_anuncio.php
require_once __DIR__ . '/../../assets/css/style_detalleTrabajo.php';
require_once __DIR__ . '/../templates/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($anuncio['titulo']) ?> - Chamba Ya</title>
</head>
<body>

    <div class="container-detalle">
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
                        <?= htmlspecialchars($anuncio['ubicacion_completa']) ?>
                    </div>
                    <div class="caja-info-mini">
                        Pago Referencial: <strong>S/. <?= number_format($anuncio['pagoReferencia'], 2) ?> / hora</strong>
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
                            <span class="label">Dirección:</span>
                            <span class="valor"><?= htmlspecialchars($anuncio['direccionEspecificas'] ?? $anuncio['direccionEspecifica'] ?? 'No especificada') ?></span>
                        </div>
                    </div>
                    
                    <hr class="divisor-linea">
                    
                    <div class="grid-detalles-secundarios">
                        <div class="item-secundario">
                            <span class="label">Estado:</span>
                            <span class="valor"><?= htmlspecialchars($anuncio['estado']) ?></span>
                        </div>
                    </div>
                    
                    <!-- Bloque de Categorías Embebidas -->
                    <div class="contenedor-tags-categorias">
                        <span class="label" style="color: #7f8c8d; font-size: 0.95rem; font-weight: 500;">Categorías:</span>
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
                        <button class="btn-postular" type="button">Contactar / Postularse</button>
                        <button class="btn-favorito" type="button">Añadir a Favoritos</button>
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
                    <strong>teléfono:</strong> <?= htmlspecialchars($anuncio['telefono'] ?? 'No especificado') ?>
                </div>
                <div class="datos-contacto-card">
                    <strong>correo:</strong> <?= htmlspecialchars($anuncio['correo']) ?>
                </div>
                
                <?php 
                    $foto = !empty($anuncio['fotoPerfil']) ? $anuncio['fotoPerfil'] : 'default.png'; 
                ?>
                <div style="margin-top: 15px;">
                    <span class="label" style="color: #7f8c8d; font-size: 0.9rem; display: block; margin-bottom: 5px;">(FotoPerfil)</span>
                    <img src="<?= BASE_URL ?>assets/uploads/img_perfiles/<?= $foto ?>" 
                        alt="Foto de Perfil del Reclutador" 
                        class="avatar-perfil-img"
                        onerror="this.src='<?= BASE_URL ?>assets/uploads/img_perfiles/default.png';">
                </div>
            </aside>
            
        </div>
    </div>
    <?php require_once __DIR__ . '/../../views/templates/footer.php'; ?>
</body>
</html>