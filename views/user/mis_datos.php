<?php
    if(!isset($_SESSION['idUsuario'])){
        header('Location: ../auth/login.php');
        exit();
    }
    require_once __DIR__ . '/../../assets/css/style.php';
    require_once __DIR__ . '/../../assets/css/styles.php';
    require_once __DIR__ . '/../../assets/css/style_profile.php';
    require_once __DIR__ . '/../../assets/css/style_portafolio.php';
    require_once __DIR__ . '/../templates/head.php';
    require_once __DIR__ . '/../templates/header.php';
?>

<div class="profile-page">

    <!-- Hero -->
    <section class="profile-hero">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>index.php">Inicio</a> &nbsp;/&nbsp; Mi Perfil
        </div>
        <h1>Mi Perfil</h1>
        <p>Administra tu información personal y preferencias de cuenta.</p>
    </section>

    <!-- Layout -->
    <div class="profile-layout">

        <!-- Sidebar -->
        <?php $paginaActual = 'mis_datos'; require_once __DIR__ . '/../templates/profile_sidebar.php'; ?>

        <!-- Content -->
        <main class="profile-content">

            <?php
                // Los mensajes van en una tabla y no en una cadena de elseif: el
                // valor de $_GET solo sirve como clave, así que nunca se imprime
                // lo que llegue por la URL.
                $mensajesEstado = [
                    'success'           => ['success', 'Tus datos han sido actualizados exitosamente.'],
                    'habilidades_ok'    => ['success', 'Tus habilidades fueron actualizadas.'],
                    'port_guardado'     => ['success', 'Trabajo añadido a tu portafolio.'],
                    'port_eliminado'    => ['success', 'Trabajo quitado del portafolio.'],
                    'email_dup'         => ['error',   'Ese correo ya está en uso por otra cuenta.'],
                    'email_invalido'    => ['error',   'Escribe un correo válido.'],
                    'telefono'          => ['error',   'El teléfono debe tener 9 dígitos.'],
                    'campos'            => ['error',   'Nombres y apellidos son obligatorios.'],
                    'port_titulo'       => ['error',   'El trabajo necesita un título.'],
                    'port_imagen'       => ['error',   'Imagen no válida. Debe ser JPG, PNG o WEBP y pesar menos de 2 MB.'],
                    'port_limite'       => ['error',   'Llegaste al máximo de trabajos en el portafolio.'],
                    'port_no_autorizado'=> ['error',   'Ese trabajo no es tuyo.'],
                    'port_error'        => ['error',   'No se pudo guardar el trabajo. Inténtalo de nuevo.'],
                    'error'             => ['error',   'Hubo un error al guardar. Inténtalo de nuevo.'],
                ];
                $estadoActual = $_GET['status'] ?? '';
            ?>
            <?php if (isset($mensajesEstado[$estadoActual])):
                [$tipoAlerta, $textoAlerta] = $mensajesEstado[$estadoActual]; ?>
                <div class="alert alert-<?= $tipoAlerta ?>">
                    <i class="fa-solid fa-<?= $tipoAlerta === 'success' ? 'circle-check' : 'circle-exclamation' ?>"></i>
                    <?= htmlspecialchars($textoAlerta) ?>
                </div>
            <?php endif; ?>

            <!-- Card: Resumen / Mini-dashboard -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="card-icon personal"><i class="fa-solid fa-chart-simple"></i></div>
                    <div>
                        <h2>Resumen de tu actividad</h2>
                        <span>Un vistazo rápido a tu perfil en Chamba Ya</span>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-tile">
                        <div class="stat-icon"><i class="fa-regular fa-eye"></i></div>
                        <div class="stat-num"><?= (int) ($estadisticas['vistas'] ?? 0) ?></div>
                        <div class="stat-label">Vistas a tus anuncios</div>
                    </div>
                    <div class="stat-tile">
                        <div class="stat-icon"><i class="fa-solid fa-inbox"></i></div>
                        <div class="stat-num"><?= (int) ($estadisticas['postulaciones'] ?? 0) ?></div>
                        <div class="stat-label">Postulaciones recibidas</div>
                    </div>
                    <div class="stat-tile">
                        <div class="stat-icon"><i class="fa-solid fa-star"></i></div>
                        <div class="stat-num">
                            <?php $cal = (float) ($estadisticas['calificacion'] ?? 0); echo $cal > 0 ? number_format($cal, 1) : '—'; ?>
                        </div>
                        <div class="stat-label">Calificación promedio</div>
                    </div>
                    <div class="stat-tile">
                        <div class="stat-icon"><i class="fa-regular fa-square-plus"></i></div>
                        <div class="stat-num"><?= (int) ($estadisticas['anuncios'] ?? 0) ?></div>
                        <div class="stat-label">Anuncios publicados</div>
                    </div>
                </div>
            </div>

            <style>
                .stats-grid{
                    display:grid;
                    grid-template-columns:repeat(4,1fr);
                    gap:16px;
                }
                .stat-tile{
                    background:linear-gradient(135deg,#f8fafc,#eff6ff);
                    border:1px solid #e2e8f0;
                    border-radius:14px;
                    padding:20px 16px;
                    text-align:center;
                    transition:transform .2s ease, box-shadow .2s ease;
                }
                .stat-tile:hover{
                    transform:translateY(-3px);
                    box-shadow:0 8px 20px rgba(37,99,235,.12);
                }
                .stat-icon{
                    width:44px;height:44px;margin:0 auto 10px;
                    display:flex;align-items:center;justify-content:center;
                    border-radius:12px;
                    background:linear-gradient(135deg,#3b82f6,#1d4ed8);
                    color:#fff;font-size:18px;
                }
                .stat-num{ font-size:30px;font-weight:800;color:#0f2847;line-height:1; }
                .stat-label{ font-size:13px;color:#64748b;margin-top:6px;font-weight:500; }
                @media (max-width:700px){ .stats-grid{ grid-template-columns:repeat(2,1fr); } }
            </style>

            <form action="<?= BASE_URL ?>controllers/AuthController.php?action=updateMisDatos" method="POST" enctype="multipart/form-data" id="profileForm">
                    <?= campoCsrf() ?>

                <!-- Card: Datos Personales -->
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="card-icon personal"><i class="fa-solid fa-user-pen"></i></div>
                        <div>
                            <h2>Datos Personales</h2>
                            <span>Tu información básica de contacto</span>
                        </div>
                        <div id="viewActions" class="card-header-actions">
                            <button type="button" class="btn-edit" id="btnEditar" onclick="activarModoEdicion()">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </button>
                        </div>
                    </div>

                    <!-- Identity: Photo + Name Preview -->
                    <div class="profile-identity">
                        <div class="photo-upload-container">
                            <div class="photo-preview">
                                <?php if(!empty($usuario['fotoPerfil'])): ?>
                                    <img id="fotoPreview" src="<?= BASE_URL ?>assets/uploads/img_perfiles/<?= htmlspecialchars($usuario['fotoPerfil']) ?>" alt="">
                                <?php else: ?>
                                    <img id="fotoPreview" src="" alt="" style="display:none; width:100%; height:100%; object-fit:cover;">
                                    <i class="fa-solid fa-user placeholder-icon" id="fotoPlaceholder"></i>
                                <?php endif; ?>
                                <!-- Solo visible en modo edición -->
                                <label for="fotoPerfilInput" class="photo-edit-btn" id="photoCameraBtn" style="display:none;">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                            </div>
                            <input type="file" id="fotoPerfilInput" name="fotoPerfil" class="photo-upload-input" accept="image/*">
                        </div>
                        <div class="profile-identity-info">
                            <h3><?= htmlspecialchars(($usuario['nombres'] ?? '') . ' ' . ($usuario['apellidos'] ?? '')) ?></h3>
                            <p><i class="fa-regular fa-envelope"></i> <?= htmlspecialchars($usuario['correo'] ?? '') ?></p>
                            <?php if(!empty($usuario['estado']) && $usuario['estado'] == 'Activo'): ?>
                                <span class="badge"><i class="fa-solid fa-circle-check"></i> Cuenta activa</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Fields -->
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombres">Nombres</label>
                            <input type="text" id="nombres" name="nombres"
                                value="<?= htmlspecialchars($usuario['nombres'] ?? '') ?>"
                                readonly class="field-readonly">
                        </div>
                        <div class="form-group">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" id="apellidos" name="apellidos"
                                value="<?= htmlspecialchars($usuario['apellidos'] ?? '') ?>"
                                readonly class="field-readonly">
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo Electrónico</label>
                            <input type="email" id="correo" name="correo"
                                value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>"
                                readonly class="field-readonly">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono"
                                value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"
                                readonly class="field-readonly">
                        </div>
                    </div>
                </div>

                <!-- Card: Datos Domiciliarios -->
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="card-icon domicilio"><i class="fa-solid fa-location-dot"></i></div>
                        <div>
                            <h2>Datos Domiciliarios</h2>
                            <span>Tu dirección y ubicación registrada</span>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="direccionDomicilio">Dirección de domicilio</label>
                            <input type="text" id="direccionDomicilio" name="direccionDomicilio"
                                value="<?= htmlspecialchars($usuario['direccionDomicilio'] ?? '') ?>"
                                readonly class="field-readonly">
                        </div>
                        <div class="form-group">
                            <label for="codigoPostal">Código Postal</label>
                            <input type="text" id="codigoPostal" name="codigoPostal"
                                value="<?= htmlspecialchars($usuario['codigoPostal'] ?? '') ?>"
                                readonly class="field-readonly">
                        </div>

                        <!-- Vista Lectura: texto plano de ubicación -->
                        <div class="form-group full-width" id="ubicacionReadonly">
                            <label>Departamento / Provincia / Distrito</label>
                            <input type="text" value="<?= htmlspecialchars($ubicacionText) ?>" readonly>
                        </div>

                        <!-- Vista Edición: tres selectores en cascada (ocultos inicialmente) -->
                        <div class="form-group full-width location-selectors" id="ubicacionEdit" style="display:none;">
                            <label>Actualizar Ubicación</label>
                            <div class="selects-grid">
                                <div class="form-group">
                                    <label for="departamento" class="sublabel">Departamento</label>
                                    <select id="departamento" name="departamento" class="profile-select">
                                        <option value="">Selecciona un departamento</option>
                                        <?php foreach($departamentos as $dep): ?>
                                            <option value="<?= $dep['idDepartamento'] ?>"
                                                <?= (!empty($loc) && $loc['idDepartamento'] == $dep['idDepartamento']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($dep['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="provincia" class="sublabel">Provincia</label>
                                    <select id="provincia" name="provincia" class="profile-select">
                                        <option value="">Selecciona una provincia</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="distrito" class="sublabel">Distrito</label>
                                    <select id="distrito" name="distrito" class="profile-select">
                                        <option value="">Selecciona un distrito</option>
                                    </select>
                                </div>
                            </div>
                            <span class="input-hint"><i class="fa-solid fa-circle-info"></i> Al guardar, la ubicación mostrada se actualizará.</span>
                        </div>
                    </div>
                </div>

                <!-- Botones de Edición (ocultos inicialmente) -->
                <div class="form-actions" id="editActions" style="display:none;">
                    <button type="button" class="btn-cancel" onclick="cancelarEdicion()">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </button>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                    </button>
                </div>

            </form>

            <!-- Card: Habilidades -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="card-icon personal"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                    <div>
                        <h2>Mis Habilidades</h2>
                        <span>Marca lo que te describe. Aparecerán en tu perfil de servicio.</span>
                    </div>
                </div>
                <form action="<?= BASE_URL ?>controllers/HabilidadController.php" method="POST">
                    <?= campoCsrf() ?>
                    <div style="display:flex;flex-wrap:wrap;gap:10px;margin:12px 0;">
                        <?php foreach(($habilidades ?? []) as $h): ?>
                            <label style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border:1px solid #cbd5e1;border-radius:20px;cursor:pointer;">
                                <input type="checkbox" name="habilidades[]" value="<?= (int) $h['idHabilidad'] ?>"
                                    <?= in_array($h['idHabilidad'], $misHabilidades ?? []) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($h['nombre']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-actions" style="margin-top:14px;">
                        <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Guardar Habilidades</button>
                    </div>
                </form>
            </div>

            <!-- Card: Portafolio de trabajos anteriores -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="card-icon personal"><i class="fa-solid fa-images"></i></div>
                    <div>
                        <h2>Mi Portafolio</h2>
                        <span>Muestra trabajos que ya hiciste. Es lo que más convence a quien te va a contratar.</span>
                    </div>
                </div>

                <?php if (!empty($portafolio)): ?>
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
                                <div class="portafolio-pie">
                                    <form method="POST" action="<?= BASE_URL ?>controllers/PortafolioController.php"
                                          onsubmit="return confirm('¿Quitar este trabajo de tu portafolio?');">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="idPortafolio" value="<?= (int) $trabajo['idPortafolio'] ?>">
                                        <button type="submit" class="portafolio-eliminar">
                                            <i class="fa-regular fa-trash-can"></i> Quitar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="portafolio-vacio">
                        Aún no has añadido ningún trabajo. Sube el primero y tu perfil se verá mucho más sólido.
                    </div>
                <?php endif; ?>

                <?php if (!empty($portafolioLleno)): ?>
                    <p style="margin-top:14px;color:#d97706;font-size:13px;">
                        Llegaste al máximo de <?= (int) PortafolioModel::MAX_POR_USUARIO ?> trabajos.
                        Quita alguno si quieres añadir otro.
                    </p>
                <?php else: ?>
                    <form action="<?= BASE_URL ?>controllers/PortafolioController.php" method="POST"
                          enctype="multipart/form-data" style="margin-top:18px;">
                        <?= campoCsrf() ?>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="portTitulo">Título del trabajo</label>
                                <input type="text" id="portTitulo" name="titulo" maxlength="120" required
                                       placeholder="Ej. Instalación eléctrica en departamento">
                            </div>
                            <div class="form-group">
                                <label for="portCategoria">Categoría</label>
                                <select id="portCategoria" name="idCategoria">
                                    <option value="">Sin categoría</option>
                                    <?php foreach (($categoriasPort ?? []) as $cat): ?>
                                        <option value="<?= (int) $cat['idCategoria'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="portDescripcion">¿Qué hiciste?</label>
                            <textarea id="portDescripcion" name="descripcion" rows="3" maxlength="1000"
                                      placeholder="Cuenta brevemente en qué consistió el trabajo."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="portImagen">Foto (opcional, máx. 2 MB — JPG, PNG o WEBP)</label>
                            <input type="file" id="portImagen" name="imagen" accept="image/jpeg,image/png,image/webp">
                        </div>
                        <div class="form-actions" style="margin-top:14px;">
                            <button type="submit" class="btn-save"><i class="fa-solid fa-plus"></i> Añadir al portafolio</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

        </main>
    </div>
</div>

<script>
    const basePath = "<?= BASE_URL ?>";

    // IDs de ubicación actuales del usuario (para pre-seleccionar en modo edición)
    const currentIdDistrito = "<?= !empty($loc) ? $loc['idDistrito'] : '' ?>";
    const currentIdProvincia = "<?= !empty($loc) ? $loc['idProvincia'] : '' ?>";

    // ===== MODO EDICIÓN =====
    function activarModoEdicion() {
        // Habilitar campos de texto
        document.querySelectorAll('.field-readonly').forEach(input => {
            input.removeAttribute('readonly');
            input.classList.add('field-editing');
        });

        // Mostrar cámara de foto
        document.getElementById('photoCameraBtn').style.display = 'flex';

        // Cambiar vista de ubicación
        document.getElementById('ubicacionReadonly').style.display = 'none';
        document.getElementById('ubicacionEdit').style.display = 'block';

        // Cambiar botones
        document.getElementById('viewActions').style.display = 'none';
        document.getElementById('editActions').style.display = 'flex';

        // Si el usuario ya tenía ubicación, pre-cargar provincias y distritos
        if (currentIdDistrito) {
            precargarUbicacion();
        }
    }

    // ===== CANCELAR EDICIÓN =====
    function cancelarEdicion() {
        location.reload();
    }

    // ===== PRE-CARGA DE UBICACIÓN =====
    async function precargarUbicacion() {
        // 1) Cargar provincias del departamento actual
        const deptSelect = document.getElementById('departamento');
        const idDepartamento = deptSelect.value;

        if (!idDepartamento) return;

        try {
            const resProvincias = await fetch(basePath + 'core/db/getProvincias.php?id=' + idDepartamento);
            const provincias = await resProvincias.json();

            const provSelect = document.getElementById('provincia');
            provSelect.innerHTML = '<option value="">Selecciona una provincia</option>';
            provincias.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.idProvincia;
                opt.textContent = p.nombre;
                if (String(p.idProvincia) === String(currentIdProvincia)) {
                    opt.selected = true;
                }
                provSelect.appendChild(opt);
            });

            // 2) Si hay provincia seleccionada, cargar sus distritos
            if (currentIdProvincia) {
                const resDistritos = await fetch(basePath + 'core/db/getDistritos.php?id=' + currentIdProvincia);
                const distritos = await resDistritos.json();

                const distSelect = document.getElementById('distrito');
                distSelect.innerHTML = '<option value="">Selecciona un distrito</option>';
                distritos.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.idDistrito;
                    opt.textContent = d.nombre;
                    if (String(d.idDistrito) === String(currentIdDistrito)) {
                        opt.selected = true;
                    }
                    distSelect.appendChild(opt);
                });
            }
        } catch(err) {
            console.error('Error al precargar ubicación:', err);
        }
    }

    // ===== CASCADA EN EDICIÓN: Departamento → Provincia =====
    document.getElementById('departamento').addEventListener('change', function() {
        const idDepartamento = this.value;
        fetch(basePath + 'core/db/getProvincias.php?id=' + idDepartamento)
            .then(res => res.json())
            .then(data => {
                const provSelect = document.getElementById('provincia');
                provSelect.innerHTML = '<option value="">Selecciona una provincia</option>';
                data.forEach(p => {
                    provSelect.innerHTML += `<option value="${p.idProvincia}">${p.nombre}</option>`;
                });
                document.getElementById('distrito').innerHTML = '<option value="">Selecciona un distrito</option>';
            });
    });

    // ===== CASCADA EN EDICIÓN: Provincia → Distrito =====
    document.getElementById('provincia').addEventListener('change', function() {
        const idProvincia = this.value;
        fetch(basePath + 'core/db/getDistritos.php?id=' + idProvincia)
            .then(res => res.json())
            .then(data => {
                const distSelect = document.getElementById('distrito');
                distSelect.innerHTML = '<option value="">Selecciona un distrito</option>';
                data.forEach(d => {
                    distSelect.innerHTML += `<option value="${d.idDistrito}">${d.nombre}</option>`;
                });
            });
    });

    // ===== PREVIEW DE FOTO =====
    document.getElementById('fotoPerfilInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('fotoPreview');
                const placeholder = document.getElementById('fotoPlaceholder');
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                if(placeholder) placeholder.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });
</script>

</body>
</html>
