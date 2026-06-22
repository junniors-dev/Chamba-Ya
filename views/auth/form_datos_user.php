<?php

    require_once __DIR__ . '/../../assets/css/style_datos.php';
?>

<!DOCTYPE html>
<html lang="es">
<body>
    <div class="form-container">
        <h1>Formulario de Datos del Usuario</h1>
        <h3>Completa la información para registrarte</h3>
        <form action="<?= $base_path ?>controllers/AuthController.php?action=completeRegister" method="POST" enctype="multipart/form-data">
            <div class="profile-picture">
                <label for="fotoPerfil">Foto de Perfil:</label>
                <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*" required>
                <p>*PNG, JPG, JPEG</p>
                <div class="profilePictureImage">
                    <img id="fotoPerfilPreview" src="" alt="Foto de Perfil">
                </div>
            </div>
            <label for="descripcionPerfil">Descripción del Perfil:</label>
            <textarea name="descripcionPerfil" id="descripcionPerfil" required></textarea>
            
            <label for="nombres">Nombres:</label>
            <input type="text" id="nombres" name="nombres" required>
            
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" required>
            
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required>
            
            <label for="direccionDomicilio">Dirección del Domicilio:</label>
            <input type="text" id="direccionDomicilio" name="direccionDomicilio" required>
            
            <label for="codigoPostal">Código Postal:</label>
            <input type="text" id="codigoPostal" name="codigoPostal" required>
            
            <div class="form-row form-triple">
                <div class="form-group">
                    <label for="departamento">Departamento:</label>
                    <select  id="departamento" name="departamento" required>
                        <option value="">Selecciona un departamento</option>
                        <?php foreach($departamentos as $departamento):?>
                            <option value="<?=$departamento['idDepartamento']?>"><?=$departamento['nombre']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="provincia">Provincia:</label>
                    <select id="provincia" name="provincia" required>
                        <option value="">Selecciona una provincia</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="distrito">Distrito:</label>
                    <select id="distrito" name="distrito" required>
                        <option value="">Selecciona un distrito</option>
                    </select>
                </div>
            </div>
            <div class="form_buttons">
                <button type="submit" name="completarRegistro">Completar Registro</button>
                <button type="button" onclick="window.location.href='../../index.php'">Cancelar</button>
            </div>
        </form>
    </div>
</body>
<script>
    const basePath = "<?= $base_path ?>";
</script>
<script src="<?= $base_path ?>assets/js/functions_form_datos.js"></script>