<?php
    require_once '../templates/head.php';
    require_once '../assets/css/style.php';
    require_once '../assets/css/style_datos.php';

    session_start();

    require_once __DIR__ . '/../assets/db/conexion.php';

    //Validación para evitar accesos directos al formulario sin
    //haber pasado por el proceso de registro previo (login.php)

    if(
        !isset($_SESSION['registro_email']) || 
        !isset($_SESSION['registro_password'])
    ){
        header('Location: login.php');
        exit();
    }

    //Aquí comienza el proceso del formulario de datos adicionales

    if(isset($_POST['completarRegistro'])){
        $email = $_SESSION['registro_email'];
        $password = $_SESSION['registro_password'];
        $descripcionPerfil = mysqli_real_escape_string ($enlace, $_POST['descripcionPerfil']);
        $nombres = mysqli_real_escape_string ($enlace, $_POST['nombres']);
        $apellidos = mysqli_real_escape_string ($enlace, $_POST['apellidos']);
        $telefono = mysqli_real_escape_string ($enlace, $_POST['telefono']);
        $direccionDomicilio = mysqli_real_escape_string ($enlace, $_POST['direccionDomicilio']);
        $codigoPostal = mysqli_real_escape_string ($enlace, $_POST['codigoPostal']);
        $idDistrito = intval($_POST['distrito']);
        $fechaRegistro = date('Y-m-d H:i:s');
        $estado = "Activo";

        //APARATADO DE FOTO DE PERFIL
        $nombreFoto = 'default.png';

        if(
            isset($_FILES['fotoPerfil']) &&
            $_FILES['fotoPerfil']['error'] === 0
        ){
            $extension = strtolower(
                pathinfo(
                    $_FILES['fotoPerfil']['name'], 
                    PATHINFO_EXTENSION
                )
            );

            $formatosPermitidos = ['png', 'jpg', 'jpeg'];

            if(!in_array($extension, $formatosPermitidos)){
                echo "
                <script>
                    alert('Formato de imagen no permitido. Por favor, sube un archivo PNG, JPG o JPEG.');
                    window.history.back();
                </script>";

                exit();
            }

            $nombreFoto = uniqid() . "." .$extension;
            $rutaDestino = __DIR__ . '/../assets/uploads/img_perfiles/' . $nombreFoto;

            move_uploaded_file(
                $_FILES['fotoPerfil']['tmp_name'], 
                $rutaDestino
            );
        }

        //Inserción del usuario en la base de datos

        $sql = "INSERT INTO usuario (
            fotoPerfil,
            nombres,
            apellidos,
            descripcionPerfil,
            telefono,
            correo,
            password,
            direccionDomicilio,
            codigoPostal,
            fechaRegistro,
            estado,
            idDistrito
            ) VALUES (
                '$nombreFoto',
                '$nombres',
                '$apellidos',
                '$descripcionPerfil',
                '$telefono',
                '$email',
                '$password',
                '$direccionDomicilio',
                '$codigoPostal',
                '$fechaRegistro',
                '$estado',
                $idDistrito
        )";

        if($enlace->query($sql)){
            $idUsuario = $enlace->insert_id;

            //INICIO DE SESIÓN AUTOMÁTICA DESPUÉS DE COMPLETAR EL REGISTRO
            $_SESSION['idUsuario'] = $idUsuario;
            $_SESSION['nombres'] = $nombres;

            //Limpiar temporales
            unset($_SESSION['registro_email']);
            unset($_SESSION['registro_password']);

            echo "
                <script>
                    alert('Registro completado exitosamente. Bienvenido a ChambaYa!');
                    window.location.href = '../index.php';
                </script>
                ";
            exit();
        }else{
            echo "
                <script>
                    alert('Error al completar el registro. Por favor, inténtalo de nuevo.');
                    window.history.back();
                </script>";
        }
    }
?>

<body>
    <div class="form-container">
        <h1>Formulario de Datos del Usuario</h1>
        <h3>Completa la información para registrarte</h3>
        <form action="" method="POST" enctype="multipart/form-data">
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
                        <?php 
                            // Obtener departamentos para el select
                            $sqlDepartamentos = "SELECT * FROM departamento";
                            $resultadoDepartamentos = $enlace->query($sqlDepartamentos);

                            while($fila = $resultadoDepartamentos->fetch_assoc()){
                                echo "<option value='" . $fila['idDepartamento'] . "'>" . $fila['nombre'] . "</option>";
                            }
                        ?>
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
                <button type="button" onclick="window.location.href='../index.php'">Cancelar</button>
            </div>
        </form>
    </div>
</body>
<script src="../assets/js/functions_form_datos.js"></script>