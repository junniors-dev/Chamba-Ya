<?php
    require_once __DIR__ . '/../core/config/autoload.php';
    require_once __DIR__ . '/../core/config/config.php';
    require_once __DIR__ . '/../core/config/session.php';

    class AuthController{

        private $userModel;

        public function __construct(){
            $this->userModel = new UserModel();
        }

        public function registerFirst(){
            iniciarSesion();

            $email = $_POST['emailInput'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            if($this->userModel->emailExists($email)){
                header('Location: ' . BASE_URL . 'views/auth/login.php?reg_status=email_exists');
                exit();
            }

            if($password !== $confirmPassword){
                header('Location: ' . BASE_URL . 'views/auth/login.php?reg_status=mismatch');
                exit();
            }

            // Misma regla que en cambiar/recuperar contraseña
            if(strlen($password) < 8){
                header('Location: ' . BASE_URL . 'views/auth/login.php?reg_status=short');
                exit();
            }

            $_SESSION['registro_email'] = $email;
            // Se guarda el HASH, nunca la contraseña en texto plano
            $_SESSION['registro_password'] = password_hash($password, PASSWORD_DEFAULT);

            header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showFormDatos');
            exit();
        }

        public function showDatosForm(){
            iniciarSesion();

            if(!isset($_SESSION['registro_email']) || !isset($_SESSION['registro_password'])){
                header('Location: ' . BASE_URL . 'views/auth/login.php');
                exit();
            }

            $departamentos = $this->userModel->getDepartamentos();

            global $base_path;
            require_once __DIR__ . '/../views/auth/form_datos_user.php';
        }

        public function login(){
            iniciarSesion();

            $correo = $_POST['emailInput'] ?? '';
            $password = $_POST['passwordInput'] ?? '';
            $recordarme = isset($_POST['recordarme']);

            $usuario = $this->userModel->getUserByEmail($correo);

            if(!$usuario){
                header('Location: ' . BASE_URL . 'views/auth/login.php?login_status=not_found');
                exit();
            }

            if(password_verify($password, $usuario['password'])){
                // Si la cuenta estaba desactivada, se reactiva al entrar.
                if(($usuario['estado'] ?? '') === 'Inactivo'){
                    $this->userModel->reactivarUsuario($usuario['idUsuario']);
                }
                $_SESSION['nombres'] = $usuario['nombres'];
                $_SESSION['idUsuario'] = $usuario['idUsuario'];
                $_SESSION['emailUsuario'] = $usuario['correo'];
                $_SESSION['rol'] = $usuario['rol'] ?? 'usuario';

                // "Recordarme": crea una cookie persistente segura (30 días).
                if($recordarme){
                    $this->crearCookieRecordarme($usuario['idUsuario']);
                }

                header('Location: ' . BASE_URL . 'index.php');
                exit();
            } else {
                header('Location: ' . BASE_URL . 'views/auth/login.php?login_status=wrong_password');
                exit();
            }
        }

        // Genera un token aleatorio: el hash va a la BD, el token plano a la cookie.
        private function crearCookieRecordarme($idUsuario): void {
            $tokenPlano = bin2hex(random_bytes(32));
            $tokenHash  = hash('sha256', $tokenPlano);
            $expira     = date('Y-m-d H:i:s', strtotime('+30 days'));

            $this->userModel->guardarRememberToken($idUsuario, $tokenHash, $expira);

            setcookie('remember_token', $tokenPlano, [
                'expires'  => strtotime('+30 days'),
                'path'     => '/',
                'httponly' => true,   // JS no puede leerla (protege de XSS)
                'samesite' => 'Lax',
            ]);
        }

        public function completeRegister(){
            iniciarSesion();

            if(!isset($_SESSION['registro_email']) || !isset($_SESSION['registro_password'])){
                header('Location: ' . BASE_URL . 'views/auth/login.php');
                exit();
            }

            $correo = $_SESSION['registro_email'];
            $password = $_SESSION['registro_password'];

            $fotoPerfil = $_FILES['fotoPerfil'] ?? null;
            $descripcionPerfil = $_POST['descripcionPerfil'] ?? '';
            $nombres = $_POST['nombres'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $direccionDomicilio = $_POST['direccionDomicilio'] ?? '';
            $codigoPostal = $_POST['codigoPostal'] ?? '';
            $fechaRegistro = date('Y-m-d H:i:s');
            $estado = 'Activo';
            // null si no eligió distrito (antes: '' rompía la FK con error fatal)
            $idDistrito = !empty($_POST['distrito']) ? $_POST['distrito'] : null;
            $nombreFoto = null; // si no sube foto, queda null (antes: variable indefinida)

            // Solo procesar la imagen si el usuario subió una
            if(isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK){

            $extension = pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION);
            $permitidos = ['jpg', 'jpeg', 'png'];

            if(!in_array(strtolower($extension), $permitidos)){
                header('Location: AuthController.php?action=showFormDatos&reg_status=bad_format');
                exit();
            }

            $maxBytes = 2 * 1024 * 1024; //maximo de bytes permitidos para la foto de perfil y asi evitar cargas de archivos pesados a la bd

            if($_FILES['fotoPerfil']['size'] > $maxBytes){
                header('Location: AuthController.php?action=showFormDatos&reg_status=too_big');
                exit();
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeReal = finfo_file($finfo, $_FILES['fotoPerfil']['tmp_name']);
            finfo_close($finfo);

            $mimesPermitidos = ['image/jpeg', 'image/png']; //mimes permitidos para las imagenes

            if(!in_array($mimeReal, $mimesPermitidos)){
                header('Location: AuthController.php?action=showFormDatos&reg_status=bad_format');
                exit();
            }

            $nombreFoto = uniqid() . '.' . $extension;

            $rutaDestino = __DIR__ . '/../assets/uploads/img_perfiles/' . $nombreFoto;

            move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $rutaDestino);
            }

            $resultado = $this->userModel->createUser(
                $nombreFoto,
                $nombres,
                $apellidos,
                $descripcionPerfil,
                $telefono,
                $correo,
                $password,
                $direccionDomicilio,
                $codigoPostal,
                $fechaRegistro,
                $estado,
                $idDistrito
            );

            if($resultado){
                $usuario = $this->userModel->getUserByEmail($correo);

                $_SESSION['idUsuario'] = $usuario['idUsuario'];
                $_SESSION['nombres'] = $usuario['nombres'];
                $_SESSION['emailUsuario'] = $usuario['correo'];
                $_SESSION['rol'] = 'usuario'; // los nuevos registros siempre son rol usuario

                unset($_SESSION['registro_email']);
                unset($_SESSION['registro_password']);

                header('Location: ' . BASE_URL . 'index.php');
                exit();
            }else{
                header('Location: AuthController.php?action=showFormDatos&reg_status=error');
                exit();
            }
        }   

        public function showMisDatos(){
            iniciarSesion();
            if(!isset($_SESSION['idUsuario'])){
                header('Location: ' . BASE_URL . 'views/auth/login.php');
                exit();
            }

            $usuario = $this->userModel->getUserById($_SESSION['idUsuario']);

            $ubicacionText = 'No registrado';
            $loc = null;
            if(!empty($usuario['idDistrito'])){
                $loc = $this->userModel->getFullLocationByIdDistrito($usuario['idDistrito']);
                if($loc){
                    $ubicacionText = $loc['departamento'] . ' / ' . $loc['provincia'] . ' / ' . $loc['distrito'];
                }
            }

            $departamentos = $this->userModel->getDepartamentos();

            require_once __DIR__ . '/../models/HabilidadModel.php';
            $habModel = new HabilidadModel();
            $habilidades = $habModel->obtenerTodas();
            $misHabilidades = $habModel->obtenerIdsDeUsuario($_SESSION['idUsuario']);

            // Resumen para el mini-dashboard del perfil.
            $estadisticas = $this->userModel->obtenerEstadisticasPerfil($_SESSION['idUsuario']);

            global $base_path;
            require_once __DIR__ . '/../views/user/mis_datos.php';
        }

        public function showSeguridad(){
            iniciarSesion();
            if(!isset($_SESSION['idUsuario'])){
                header('Location: ' . BASE_URL . 'views/auth/login.php');
                exit();
            }
            global $base_path;
            require_once __DIR__ . '/../views/user/seguridad.php';
        }

        public function showPreferencias(){
            iniciarSesion();
            if(!isset($_SESSION['idUsuario'])){
                header('Location: ' . BASE_URL . 'views/auth/login.php');
                exit();
            }
            $preferencias = $this->userModel->getPreferencias($_SESSION['idUsuario']);
            global $base_path;
            require_once __DIR__ . '/../views/user/preferencias.php';
        }

        public function guardarPreferencias(){
            iniciarSesion();
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){ die('Método no permitido'); }
            if(!isset($_SESSION['idUsuario'])){ die('No autorizado'); }

            $id = $_SESSION['idUsuario'];
            $ofertas = isset($_POST['notif_ofertas']) ? 1 : 0;
            $vistas  = isset($_POST['notif_vistas'])  ? 1 : 0;
            $boletin = isset($_POST['notif_boletin']) ? 1 : 0;

            $validas = ['publico', 'solo_empresas', 'oculto'];
            $visibilidad = in_array($_POST['visibilidad'] ?? '', $validas) ? $_POST['visibilidad'] : 'publico';

            $this->userModel->guardarPreferencias($id, $ofertas, $vistas, $boletin, $visibilidad);
            header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showPreferencias&pref_status=success');
            exit();
        }

        public function desactivarCuenta(){
            iniciarSesion();
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){ die('Método no permitido'); }
            if(!isset($_SESSION['idUsuario'])){ die('No autorizado'); }
            $this->userModel->desactivarUsuario($_SESSION['idUsuario']);
            $_SESSION = [];
            session_destroy();
            header('Location: ' . BASE_URL . 'views/auth/login.php?login_status=cuenta_desactivada');
            exit();
        }

        public function eliminarCuenta(){
            iniciarSesion();
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){ die('Método no permitido'); }
            if(!isset($_SESSION['idUsuario'])){ die('No autorizado'); }
            $this->userModel->eliminarCuentaCompleta($_SESSION['idUsuario']);
            $_SESSION = [];
            session_destroy();
            header('Location: ' . BASE_URL . 'index.php');
            exit();
        }

        public function updateMisDatos(){
            iniciarSesion();
            if(!isset($_SESSION['idUsuario'])){
                die('No autorizado');
            }

            $idUsuario = $_SESSION['idUsuario'];
            $nombres = $_POST['nombres'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $direccionDomicilio = $_POST['direccionDomicilio'] ?? '';
            $codigoPostal = $_POST['codigoPostal'] ?? '';
            $idDistrito = !empty($_POST['distrito']) ? $_POST['distrito'] : null;

            // No permitir cambiar el correo a uno que ya usa otra cuenta.
            if($this->userModel->correoEnUsoPorOtro($correo, $idUsuario)){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=email_dup');
                exit();
            }

            $fotoPerfilData = null;
            if(isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == UPLOAD_ERR_OK){
                $extension = strtolower(pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION));
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                if(!in_array($extension, $permitidos)){
                    header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=error'); exit();
                }

                // Validación del archivo: tamaño máximo y tipo MIME real
                $maxBytes = 2 * 1024 * 1024; // 2 MB
                if($_FILES['fotoPerfil']['size'] > $maxBytes){
                    header('Location: AuthController.php?action=showMisDatos&status=error'); exit();
                }
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeReal = finfo_file($finfo, $_FILES['fotoPerfil']['tmp_name']);
                finfo_close($finfo);
                $mimesPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
                if(!in_array($mimeReal, $mimesPermitidos)){
                    header('Location: AuthController.php?action=showMisDatos&status=error'); exit();
                }

                $nombreFoto = uniqid('pfp_') . '.' . $extension;
                $rutaDestino = __DIR__ . '/../assets/uploads/img_perfiles/' . $nombreFoto;
                if(move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $rutaDestino)){
                    $fotoPerfilData = $nombreFoto;
                }
            }

            $success = $this->userModel->updateUserProfileData(
                $idUsuario, 
                $nombres, 
                $apellidos, 
                $correo, 
                $telefono, 
                $direccionDomicilio, 
                $codigoPostal,
                $idDistrito,
                $fotoPerfilData
            );

            if($success){
                $_SESSION['nombres'] = $nombres;
                $_SESSION['emailUsuario'] = $correo;
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=success');
            }else{
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=error');
            }
        }

        public function changePassword(){
            iniciarSesion();
            if(!isset($_SESSION['idUsuario'])){
                die('No autorizado');
            }

            $idUsuario = $_SESSION['idUsuario'];
            $currentPassword = $_POST['currentPassword'] ?? '';
            $newPassword = $_POST['newPassword'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            if(empty($currentPassword) || empty($newPassword) || empty($confirmPassword)){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=empty'); exit();
            }
            if($newPassword !== $confirmPassword){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=mismatch'); exit();
            }
            if(strlen($newPassword) < 8){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=short'); exit();
            }

            $usuario = $this->userModel->getUserById($idUsuario);
            if(!$usuario || !password_verify($currentPassword, $usuario['password'])){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=wrong'); exit();
            }

            $success = $this->userModel->updatePassword($idUsuario, $newPassword);
            if($success){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=success');
            } else {
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=error');
            }
        }

        public function showRecuperar(){
            iniciarSesion();
            global $base_path;
            require_once __DIR__ . '/../views/auth/recuperar_password.php';
        }

        public function recuperarPassword(){
            iniciarSesion();

            $correo          = trim($_POST['emailInput'] ?? '');
            $telefono        = trim($_POST['telefonoInput'] ?? '');
            $newPassword     = $_POST['newPassword'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            if(empty($correo) || empty($telefono) || empty($newPassword) || empty($confirmPassword)){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showRecuperar&rec_status=empty'); exit();
            }
            if($newPassword !== $confirmPassword){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showRecuperar&rec_status=mismatch'); exit();
            }
            if(strlen($newPassword) < 8){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showRecuperar&rec_status=short'); exit();
            }

            // Verifica con correo + telefono (metodo simple, sin email).
            $usuario = $this->userModel->getUserByEmail($correo);
            if(!$usuario || trim((string)$usuario['telefono']) !== $telefono){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showRecuperar&rec_status=not_match'); exit();
            }

            $success = $this->userModel->updatePassword($usuario['idUsuario'], $newPassword);
            if($success){
                header('Location: ' . BASE_URL . 'views/auth/login.php?login_status=pass_reset');
            } else {
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showRecuperar&rec_status=error');
            }
            exit();
        }
    }

    $controller = new AuthController();

    $action = $_GET['action'] ?? '';

    switch($action){
        case 'registerFirst':
            $controller->registerFirst();
            break;
        case 'showFormDatos':
            $controller->showDatosForm();
            break;
        case 'login':
            $controller->login();
            break;
        case 'completeRegister':
            $controller->completeRegister();
            break;
        case 'showMisDatos':
            $controller->showMisDatos();
            break;
        case 'showSeguridad':
            $controller->showSeguridad();
            break;
        case 'showPreferencias':
            $controller->showPreferencias();
            break;
        case 'guardarPreferencias':
            $controller->guardarPreferencias();
            break;
        case 'desactivarCuenta':
            $controller->desactivarCuenta();
            break;
        case 'eliminarCuenta':
            $controller->eliminarCuenta();
            break;
        case 'updateMisDatos':
            $controller->updateMisDatos();
            break;
        case 'changePassword':
            $controller->changePassword();
            break;
        case 'showRecuperar':
            $controller->showRecuperar();
            break;
        case 'recuperarPassword':
            $controller->recuperarPassword();
            break;
        default:
            die('Acción no válida');
    }
?>