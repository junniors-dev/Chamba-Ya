<?php
    require_once __DIR__ . '/../../core/config/session.php';
    iniciarSesion();
    
    if(!isset($_SESSION['idUsuario'])){
        header('Location: ../auth/login.php');
        exit();
    }
    
    require_once __DIR__ . '/../../controllers/AnuncioCreadoController.php';
    $conAnuncio = new AnuncioCreadoController();
    $mis_anuncios = $conAnuncio->obtenerAnuncios();
    $categorias = $conAnuncio->obtenerCategorias();
    $departamentos = $conAnuncio->obtenerDepartamentos();

    require_once __DIR__ . '/../../assets/css/style.php';
    require_once __DIR__ . '/../../assets/css/styles.php';
    require_once __DIR__ . '/../../assets/css/style_profile.php';
    require_once __DIR__ . '/../templates/head.php';
    require_once __DIR__ . '/../templates/header.php';
    require_once __DIR__ . '/../../assets/css/styles_anuncios.php'
?>

<div class="profile-page">

    <!-- Hero -->
    <section class="profile-hero">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>index.php">Inicio</a> &nbsp;/&nbsp; Mi Perfil
        </div>
        <h1>Mis Anuncios</h1>
        <p>Administra los anuncios y servicios que has publicado.</p>
    </section>

    <!-- Layout -->
    <div class="profile-layout">

        <!-- Sidebar -->
        <?php $paginaActual = 'anuncios'; require_once __DIR__ . '/../templates/profile_sidebar.php'; ?>

        <!-- Content -->
        <main class="profile-content">
            <!-- SECCIÓN: ANUNCIOS CREADOS -->
            <section id="seccion-anuncios" class="vista-seccion activo" style="display: block;">
                <div class="encabezado-seccion">
                    <h2>Anuncios Activos</h2>
                    <button class="boton-crear" onclick="prepararCreacion()"><i class="fas fa-plus"></i> Crear Nuevo Anuncio</button> 
                </div>
                <div id="lista-anuncios">
                    <?php if (empty($mis_anuncios)): ?>
                        <p>Actualmente no tienes anuncios creados</p>
                    <?php else: ?>
                        <?php foreach ($mis_anuncios as $anuncio): ?>
                            <div class="tarjeta-horizontal">
                                <div class="imagen-tarjeta"><i class="fas fa-image fa-2x"></i></div>
                                <div class="cuerpo-tarjeta">
                                    <h3><?php echo htmlspecialchars($anuncio['titulo']); ?></h3>
                                    <p><?php echo htmlspecialchars($anuncio['descripcion']); ?></p>
                                    <?php if (!empty($anuncio['distrito_nombre'])): ?>
                                        <p style="font-size: 0.85rem; color: #666; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                            <i class="fas fa-map-marker-alt" style="color: var(--purpura-principal);"></i> 
                                            <?php 
                                                $loc = htmlspecialchars($anuncio['distrito_nombre']);
                                                if (!empty($anuncio['provincia_nombre'])) $loc .= ', ' . htmlspecialchars($anuncio['provincia_nombre']);
                                                if (!empty($anuncio['departamento_nombre'])) $loc .= ' - ' . htmlspecialchars($anuncio['departamento_nombre']);
                                                if (!empty($anuncio['direccionEspecifica'])) $loc .= ' (' . htmlspecialchars($anuncio['direccionEspecifica']) . ')';
                                                echo $loc;
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                    <span class="etiqueta-estado estado-<?php echo str_replace(' ', '', $anuncio['estado']); ?>">
                                        <?php echo ucfirst($anuncio['estado']); ?>
                                    </span>
                                </div>
                                <div class="acciones-tarjeta">
                                    <button class="boton-accion" title="Editar" onclick="abrirEdicion(this)"
                                            data-id="<?php echo $anuncio['id']; ?>"
                                            data-titulo="<?php echo htmlspecialchars($anuncio['titulo'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-descripcion="<?php echo htmlspecialchars($anuncio['descripcion'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-estado="<?php echo $anuncio['estado']; ?>"
                                            data-departamento_id="<?php echo $anuncio['idDepartamento'] ?? ''; ?>"
                                            data-provincia_id="<?php echo $anuncio['idProvincia'] ?? ''; ?>"
                                            data-distrito_id="<?php echo $anuncio['idDistrito'] ?? ''; ?>"
                                            data-direccion_especifica="<?php echo htmlspecialchars($anuncio['direccionEspecifica'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                            data-pago="<?php echo $anuncio['pagoReferencia']; ?>"
                                            data-modalidad="<?php echo $anuncio['modalidad']; ?>"
                                            data-tipo="<?php echo $anuncio['tipoAnuncio']; ?>"
                                            data-categorias_ids="<?php echo htmlspecialchars($anuncio['categorias_ids'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                            data-categorias_nombres="<?php echo htmlspecialchars($anuncio['categorias_nombres'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="<?= BASE_URL ?>controllers/AnuncioCreadoController.php" method="POST" class="form-eliminar">
                    <?= campoCsrf() ?>
                                        <input type="hidden" name="id" value="<?php echo $anuncio['id']; ?>">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <button class="boton-accion" type="submit" onclick="return confirm('¿Estás seguro de que quieres eliminar este anuncio?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div> 
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</div>

<!-- MODAL: CREAR ANUNCIO -->
<div id="modal-crear" class="superposicion-modal">
    <div class="contenido-modal">
        <span class="cerrar-modal" onclick="cerrarModal('modal-crear')">&times;</span>
        <h3>Publicar Nueva Chamba</h3>
        <form id="formulario-anuncio" action="<?= BASE_URL ?>controllers/AnuncioCreadoController.php" method="POST">
                    <?= campoCsrf() ?>
            <input type="hidden" name="id" id="form-id-anuncio">
            <div class="grupo-formulario">
                <label>Título del Anuncio</label>
                <input type="text" name="titulo" id="titulo-chamba" placeholder="Ej: Ayudante de cocina" required>
            </div>
            <div class="grupo-formulario">
                <label>Descripción detallada</label>
                <textarea name="descripcion" id="desc-chamba" rows="3" maxlength="200" oninput="actualizarContador(this)"></textarea>
                <small id="contador-chars" class="contador-derecha">0/200</small>
            </div>
            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label>Departamento</label>
                    <select name="departamento_id" id="departamento-chamba">
                        <option value="">Seleccione Departamento</option>
                        <?php foreach ($departamentos as $dep): ?>
                            <option value="<?php echo $dep['idDepartamento']; ?>">
                                <?php echo htmlspecialchars($dep['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grupo-formulario">
                    <label>Provincia</label>
                    <select name="provincia_id" id="provincia-chamba" disabled>
                        <option value="">Seleccione Provincia</option>
                    </select>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label>Distrito</label>
                    <select name="distrito_id" id="distrito-chamba" disabled required>
                        <option value="">Seleccione Distrito</option>
                    </select>
                </div>
                <div class="grupo-formulario">
                    <label>Dirección Específica</label>
                    <input type="text" name="direccion_especifica" id="direccion-especifica-chamba" placeholder="Ej: Av. Larco 123">
                </div>
            </div>
            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label>Pago Referencial (S/.)</label>
                    <input type="number" name="pago" id="pago-chamba" placeholder="0.00">
                </div>
                <div class="grupo-formulario">
                    <label>Modalidad</label>
                    <select name="modalidad" id="modalidad-chamba">
                        <option value="Presencial">Presencial</option>
                        <option value="Virtual">Virtual</option>
                    </select>
                </div>
            </div>
            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label>Tipo de Anuncio</label>
                    <select name="tipo" id="tipo-chamba">
                        <option value="Trabajo">Busco Empleado (Trabajo)</option>
                        <option value="Servicio">Ofrezco mis servicios</option>
                    </select>
                </div>
            </div>
            <div class="grupo-formulario">
                <label>Estado</label>
                <select name="estado" id="estado-chamba">
                    <option value="activo">Activo</option>
                    <option value="en_proceso">En proceso</option>
                    <option value="finalizado">Finalizado</option>
                    <option value="oculto">Oculto</option>
                </select>
            </div>
            <div class="grupo-formulario">
                <label>Categorías</label>
                <div class="selector-etiquetas">
                    <?php foreach ($categorias as $cat): ?>
                        <span class="opcion-etiqueta" onclick="agregarEtiqueta('<?php echo htmlspecialchars($cat['nombre'], ENT_QUOTES, 'UTF-8'); ?>', <?php echo $cat['idCategoria']; ?>)">
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <div id="contenedor-etiquetas" class="selector-etiquetas"></div>
                <input type="hidden" name="categorias_ids" id="categorias-ids" value="">
            </div>
            <button type="submit" class="boton-crear boton-ancho-total">Publicar Ahora</button>
        </form>
    </div>
</div>

<div id="contenedor-notificaciones"></div>

<script src="<?= BASE_URL ?>assets/js/script_Anuncios.js?v=<?php echo time(); ?>"></script>
<?php if (isset($_GET['mensaje'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        let msg = "";
        const m = "<?php echo htmlspecialchars($_GET['mensaje'], ENT_QUOTES, 'UTF-8'); ?>";
        if (m === "guardado") msg = "¡Anuncio publicado con éxito!";
        else if (m === "actualizado") msg = "¡Anuncio actualizado con éxito!";
        else if (m === "eliminado") msg = "¡Anuncio eliminado con éxito!";
        else if (m === "error_campos") msg = "Error: Por favor completa todos los campos obligatorios.";
        else if (m === "error_guardar") msg = "Error: No se pudo guardar el anuncio en la base de datos.";
        else if (m === "error_eliminar") msg = "Error: No se pudo eliminar el anuncio.";
        else if (m === "no_autorizado") msg = "Error: No tienes permiso para modificar este anuncio.";
        if (msg) mostrarNotificacion(msg);
    });
</script>
<?php endif; ?>

</body>
</html>
