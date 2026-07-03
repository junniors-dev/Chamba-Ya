<?php
    require_once __DIR__ . '/../../core/config/config.php';
    require_once __DIR__ . '/../../assets/css/style_datos.php';
?>

<!DOCTYPE html>
<html lang="es">
<body>
<div class="form-container">
    <!-- CABECERA -->
    <div class="form-header">
        <div class="header-icon">
            <i class="fa-solid fa-user"></i>
        </div>
        <div>
            <h1>Completa tu perfil</h1>
            <h3>Solo falta un paso para comenzar a usar Chamba Ya</h3>
        </div>
    </div>
    <?php if(isset($_GET['reg_status'])): ?>
        <?php
            $regMsgs = [
                'bad_format' => 'Imagen no válida. Solo se permiten JPG o PNG.',
                'too_big'    => 'La imagen supera el tamaño máximo (2 MB).',
                'error'      => 'No se pudo completar el registro. Intenta de nuevo.',
            ];
            $msg = $regMsgs[$_GET['reg_status']] ?? 'Ocurrió un error en el registro.';
        ?>
        <p class="form_msg form_msg_error">
            <?= htmlspecialchars($msg) ?>
        </p>
    <?php endif; ?>
    <form action="<?= BASE_URL ?>controllers/AuthController.php?action=completeRegister" method="POST" enctype="multipart/form-data">
        <!-- FOTO + DESCRIPCIÓN -->
        <div class="profile-section">
            <div class="profile-picture">
                <label for="fotoPerfil">
                    Foto de perfil (Opcional)
                </label>
                <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*">
                <p> PNG, JPG o JPEG · Máximo 2 MB</p>
            </div>
            <div class="profilePictureImage">
                <img id="fotoPerfilPreview" src="" alt="Foto de Perfil">
            </div>
        </div>
        <div class="form-group">
            <label for="descripcionPerfil">
                Descripción del Perfil
            </label>
            <textarea
                id="descripcionPerfil" name="descripcionPerfil" placeholder="Cuéntanos sobre tu experiencia, habilidades o aquello que quieras que otros usuarios conozcan..." required></textarea>
        </div>
        <!-- DATOS PERSONALES -->
        <div class="form-row">
            <div class="form-group">
                <label for="nombres">Nombres</label>
                <input
                    type="text" id="nombres" name="nombres" placeholder="Ej. Juan Carlos" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input
                    type="text" id="apellidos" name="apellidos" placeholder="Ej. Pérez Gómez" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="987654321" required>
            </div>
            <div class="form-group">
                <label for="codigoPostal">Código Postal</label>
                <input type="text" id="codigoPostal" name="codigoPostal" placeholder="Código Postal" required>
            </div>
        </div>
        <!-- DIRECCIÓN -->
        <div class="form-group">
            <label for="direccionDomicilio"> Dirección del domicilio</label>
            <input type="text" id="direccionDomicilio" name="direccionDomicilio" placeholder="Av., Calle, Urbanización..." required>
        </div>
        <!-- UBICACIÓN -->
        <div class="form-row">
            <div class="form-group">
                <label for="departamento">Departamento</label>
                <select id="departamento" name="departamento" required>
                    <option value="">
                        Selecciona un departamento
                    </option>
                    <?php foreach($departamentos as $departamento): ?>
                        <option value="<?= $departamento['idDepartamento'] ?>">
                            <?= $departamento['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="provincia">Provincia</label>
                <select id="provincia" name="provincia" required>
                    <option value=""> Selecciona una provincia</option>
                </select>
            </div>
            <div class="form-group">
                <label for="distrito">Distrito</label>
                <select id="distrito" name="distrito" required>
                    <option value="">Selecciona un distrito</option>
                </select>
            </div>
        </div>
        <!-- BOTONES -->
        <div class="form_buttons">
            <button class="btn-primary" type="submit" name="completarRegistro">
                <i class="fa-solid fa-circle-check"></i>Completar Registro
            </button>
            <button class="btn-secondary" type="button" onclick="window.location.href='<?= BASE_URL ?>index.php'">
                <i class="fa-solid fa-xmark"></i>
                Cancelar
            </button>
        </div>
    </form>
</div>
</body>
<script>const basePath = "<?= BASE_URL ?>";</script>
<script src="<?= BASE_URL ?>assets/js/functions_form_datos.js"></script>
</html>