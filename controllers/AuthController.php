<?php
    require_once __DIR__ . '/../core/config/autoload.php';
    require_once __DIR__ . '/../core/config/config.php';
    require_once __DIR__ . '/../core/config/session.php';
    require_once __DIR__ . '/../core/config/email.php';
    require_once __DIR__ . '/../core/security/csrf.php';
    require_once __DIR__ . '/../core/security/validacion.php';

    class AuthController{

        /* ================== Política de intentos de inicio de sesión ==================
           El contador se guarda en la base de datos (tabla intento_login), no en la
           sesión: si vive en $_SESSION, el atacante lo borra con su propia cookie y
           el bloqueo se evade en un segundo. */
        const MAX_INTENTOS_LOGIN = 5;    // fallos permitidos dentro de la ventana
        const VENTANA_INTENTOS   = 900;  // ventana de 15 minutos

        // Validez del enlace de recuperación de contraseña.
        const RESET_VALIDEZ_MINUTOS = 30;

        private $userModel;

        public function __construct(){
            $this->userModel = new UserModel();
        }

        /** IP del visitante, para el control de intentos. */
        private function ipCliente(): string {
            return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        }

        /* ============================================================
           REGISTRO
           ============================================================ */

        public function registerFirst(){
            iniciarSesion();
            verificarCsrf();

            $email           = normalizarCorreo($_POST['emailInput'] ?? '');
            $password        = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            // Validación de formato en el servidor: el type="email" del HTML es solo
            // una ayuda visual, cualquiera puede enviar un POST saltándose el formulario.
            if(!esCorreoValido($email)){
                header('Location: ' . BASE_URL . 'views/auth/login.php?reg_status=email_invalido');
                exit();
            }

            if($this->userModel->emailExists($email)){
                header('Location: ' . BASE_URL . 'views/auth/login.php?reg_status=email_exists');
                exit();
            }

            if($password !== $confirmPassword){
                header('Location: ' . BASE_URL . 'views/auth/login.php?reg_status=mismatch');
                exit();
            }

            // Mínimo 8 y máximo 72: bcrypt trunca en silencio a partir de 72 bytes.
            if(!esPasswordValida($password)){
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

        public function completeRegister(){
            iniciarSesion();
            verificarCsrf();

            if(!isset($_SESSION['registro_email']) || !isset($_SESSION['registro_password'])){
                header('Location: ' . BASE_URL . 'views/auth/login.php');
                exit();
            }

            $correo   = $_SESSION['registro_email'];
            $password = $_SESSION['registro_password'];

            $descripcionPerfil  = limpiarTexto($_POST['descripcionPerfil'] ?? '', 1000);
            $nombres            = limpiarTexto($_POST['nombres'] ?? '', 100);
            $apellidos          = limpiarTexto($_POST['apellidos'] ?? '', 100);
            $telefono           = normalizarTelefono($_POST['telefono'] ?? '');
            $direccionDomicilio = limpiarTexto($_POST['direccionDomicilio'] ?? '', 200);
            $codigoPostal       = limpiarTexto($_POST['codigoPostal'] ?? '', 5);
            $fechaRegistro      = date('Y-m-d H:i:s');
            $estado             = 'Activo';
            // null si no eligió distrito (antes: '' rompía la FK con error fatal)
            $idDistrito = !empty($_POST['distrito']) ? (int) $_POST['distrito'] : null;
            $nombreFoto = null; // si no sube foto, queda null

            // Campos obligatorios: se validan aquí y no solo con "required" en el HTML.
            if($nombres === '' || $apellidos === ''){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showFormDatos&reg_status=campos');
                exit();
            }
            if($telefono !== '' && !esTelefonoValido($telefono)){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showFormDatos&reg_status=telefono');
                exit();
            }

            // Solo procesar la imagen si el usuario subió una
            if(isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK){

                $extension  = strtolower(pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION));
                $permitidos = ['jpg', 'jpeg', 'png'];

                if(!in_array($extension, $permitidos, true)){
                    header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showFormDatos&reg_status=bad_format');
                    exit();
                }

                $maxBytes = 2 * 1024 * 1024; // 2 MB
                if($_FILES['fotoPerfil']['size'] > $maxBytes){
                    header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showFormDatos&reg_status=too_big');
                    exit();
                }

                // Comprueba el tipo REAL del archivo, no la extensión: renombrar
                // un .php a .jpg no cambia su contenido.
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeReal = finfo_file($finfo, $_FILES['fotoPerfil']['tmp_name']);
                finfo_close($finfo);

                if(!in_array($mimeReal, ['image/jpeg', 'image/png'], true)){
                    header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showFormDatos&reg_status=bad_format');
                    exit();
                }

                // El nombre lo genera el servidor: nunca se usa el del cliente,
                // que podría contener "../" y escribir fuera de la carpeta.
                $nombreFoto  = bin2hex(random_bytes(8)) . '.' . $extension;
                $rutaDestino = __DIR__ . '/../assets/uploads/img_perfiles/' . $nombreFoto;

                if(!move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $rutaDestino)){
                    $nombreFoto = null;
                }
            }

            $resultado = $this->userModel->createUser(
                $nombreFoto, $nombres, $apellidos, $descripcionPerfil, $telefono,
                $correo, $password, $direccionDomicilio, $codigoPostal,
                $fechaRegistro, $estado, $idDistrito
            );

            if($resultado){
                $usuario = $this->userModel->getUserByEmail($correo);

                // La sesión pasa de anónima a autenticada: se cambia el identificador.
                regenerarSesion();

                $_SESSION['idUsuario']    = $usuario['idUsuario'];
                $_SESSION['nombres']      = $usuario['nombres'];
                $_SESSION['emailUsuario'] = $usuario['correo'];
                $_SESSION['rol']          = 'usuario'; // los nuevos registros siempre son rol usuario

                unset($_SESSION['registro_email'], $_SESSION['registro_password']);

                header('Location: ' . BASE_URL . 'index.php');
                exit();
            }

            header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showFormDatos&reg_status=error');
            exit();
        }

        /* ============================================================
           INICIO DE SESIÓN
           ============================================================ */

        public function login(){
            iniciarSesion();
            verificarCsrf();

            $correo     = normalizarCorreo($_POST['emailInput'] ?? '');
            $password   = $_POST['passwordInput'] ?? '';
            $recordarme = isset($_POST['recordarme']);
            $ip         = $this->ipCliente();

            // Bloqueo por intentos fallidos, contados en la base de datos.
            $fallos = $this->userModel->contarIntentosFallidos($correo, $ip, self::VENTANA_INTENTOS);
            if($fallos >= self::MAX_INTENTOS_LOGIN){
                header('Location: ' . BASE_URL . 'views/auth/login.php?login_status=bloqueado&espera=' . self::VENTANA_INTENTOS);
                exit();
            }

            $usuario = $this->userModel->getUserByEmail($correo);

            // Si el correo no existe se compara igualmente contra un hash ficticio.
            // Así el tiempo de respuesta es parecido en ambos casos y no se puede
            // deducir qué correos están registrados midiendo la demora.
            $hashGuardado = $usuario['password'] ?? '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRS';
            $passwordOk   = password_verify($password, $hashGuardado);

            if(!$usuario || !$passwordOk){
                $this->userModel->registrarIntentoLogin($correo, $ip, false);
                // Mensaje único: no se distingue "correo no existe" de "contraseña
                // incorrecta", porque esa diferencia le confirma al atacante qué
                // cuentas existen (enumeración de usuarios).
                header('Location: ' . BASE_URL . 'views/auth/login.php?login_status=credenciales');
                exit();
            }

            // Se distingue QUIÉN desactivó la cuenta, porque antes no se distinguía y
            // cualquier estado se reactivaba solo al iniciar sesión: un usuario
            // suspendido por el administrador volvía a entrar como si nada.
            //
            //   Inactivo               -> el propio usuario se dio de baja: puede volver.
            //   Suspendido / Bloqueado -> sanción del administrador: no entra.
            $estado = $usuario['estado'] ?? 'Activo';

            if($estado === 'Suspendido' || $estado === 'Bloqueado'){
                $this->userModel->registrarIntentoLogin($correo, $ip, false);
                header('Location: ' . BASE_URL . 'views/auth/login.php?login_status=cuenta_bloqueada');
                exit();
            }

            if($estado === 'Inactivo'){
                // Baja voluntaria: al iniciar sesión la cuenta vuelve a estar activa.
                $this->userModel->reactivarUsuario($usuario['idUsuario']);
            }

            // Credenciales correctas.
            $this->userModel->registrarIntentoLogin($correo, $ip, true);
            $this->userModel->limpiarIntentosFallidos($correo, $ip);

            // Defensa contra "session fixation": si un atacante había fijado un id de
            // sesión antes del login, ese id deja de valer justo ahora.
            regenerarSesion();

            $_SESSION['nombres']      = $usuario['nombres'];
            $_SESSION['idUsuario']    = $usuario['idUsuario'];
            $_SESSION['emailUsuario'] = $usuario['correo'];
            $_SESSION['rol']          = $usuario['rol'] ?? 'usuario';

            // "Recordarme": cookie persistente de 30 días.
            if($recordarme){
                $this->crearCookieRecordarme($usuario['idUsuario']);
            }

            header('Location: ' . BASE_URL . 'index.php');
            exit();
        }

        // Genera un token aleatorio: el hash va a la BD, el token plano a la cookie.
        // Si alguien lee la base de datos no obtiene una cookie utilizable.
        private function crearCookieRecordarme($idUsuario): void {
            $tokenPlano = bin2hex(random_bytes(32));
            $tokenHash  = hash('sha256', $tokenPlano);

            $this->userModel->guardarRememberToken($idUsuario, $tokenHash, 30);

            setcookie('remember_token', $tokenPlano, [
                'expires'  => strtotime('+30 days'),
                'path'     => '/',
                'httponly' => true,   // JS no puede leerla (protege de XSS)
                'samesite' => 'Lax',  // no viaja en peticiones desde otros sitios
                'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            ]);
        }

        /* ============================================================
           PERFIL Y AJUSTES
           ============================================================ */

        public function showMisDatos(){
            requireLogin();

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
            $habModel       = new HabilidadModel();
            $habilidades    = $habModel->obtenerTodas();
            $misHabilidades = $habModel->obtenerIdsDeUsuario($_SESSION['idUsuario']);

            // Resumen para el mini-dashboard del perfil.
            $estadisticas = $this->userModel->obtenerEstadisticasPerfil($_SESSION['idUsuario']);

            global $base_path;
            require_once __DIR__ . '/../views/user/mis_datos.php';
        }

        public function showSeguridad(){
            requireLogin();
            global $base_path;
            require_once __DIR__ . '/../views/user/seguridad.php';
        }

        public function showPreferencias(){
            requireLogin();
            $preferencias = $this->userModel->getPreferencias($_SESSION['idUsuario']);
            global $base_path;
            require_once __DIR__ . '/../views/user/preferencias.php';
        }

        public function guardarPreferencias(){
            requireLogin();
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){ die('Método no permitido'); }
            verificarCsrf();

            $id      = $_SESSION['idUsuario'];
            $ofertas = isset($_POST['notif_ofertas']) ? 1 : 0;
            $vistas  = isset($_POST['notif_vistas'])  ? 1 : 0;
            $boletin = isset($_POST['notif_boletin']) ? 1 : 0;

            $validas     = ['publico', 'solo_empresas', 'oculto'];
            $visibilidad = in_array($_POST['visibilidad'] ?? '', $validas, true) ? $_POST['visibilidad'] : 'publico';

            $this->userModel->guardarPreferencias($id, $ofertas, $vistas, $boletin, $visibilidad);
            header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showPreferencias&pref_status=success');
            exit();
        }

        public function desactivarCuenta(){
            requireLogin();
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){ die('Método no permitido'); }
            verificarCsrf();

            $id = $_SESSION['idUsuario'];
            $this->userModel->desactivarUsuario($id);
            // La cookie "recordarme" también se anula: si no, volvería a entrar solo.
            $this->userModel->limpiarRememberToken($id);
            borrarCookieRecordarme();
            cerrarSesion();

            header('Location: ' . BASE_URL . 'views/auth/login.php?login_status=cuenta_desactivada');
            exit();
        }

        public function eliminarCuenta(){
            requireLogin();
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){ die('Método no permitido'); }
            verificarCsrf();

            // Borrar la cuenta es irreversible: se exige la contraseña actual, para
            // que un enlace malicioso no pueda provocarlo con un solo clic.
            $password = $_POST['passwordConfirmacion'] ?? '';
            $usuario  = $this->userModel->getUserById($_SESSION['idUsuario']);
            if(!$usuario || !password_verify($password, $usuario['password'])){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=wrong');
                exit();
            }

            $this->userModel->eliminarCuentaCompleta($_SESSION['idUsuario']);
            borrarCookieRecordarme();
            cerrarSesion();

            header('Location: ' . BASE_URL . 'index.php');
            exit();
        }

        public function updateMisDatos(){
            requireLogin();
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){ die('Método no permitido'); }
            verificarCsrf();

            $idUsuario          = $_SESSION['idUsuario'];
            $nombres            = limpiarTexto($_POST['nombres'] ?? '', 100);
            $apellidos          = limpiarTexto($_POST['apellidos'] ?? '', 100);
            $correo             = normalizarCorreo($_POST['correo'] ?? '');
            $telefono           = normalizarTelefono($_POST['telefono'] ?? '');
            $direccionDomicilio = limpiarTexto($_POST['direccionDomicilio'] ?? '', 200);
            $codigoPostal       = limpiarTexto($_POST['codigoPostal'] ?? '', 5);
            $idDistrito         = !empty($_POST['distrito']) ? (int) $_POST['distrito'] : null;

            if($nombres === '' || $apellidos === ''){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=campos');
                exit();
            }
            if(!esCorreoValido($correo)){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=email_invalido');
                exit();
            }
            if($telefono !== '' && !esTelefonoValido($telefono)){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=telefono');
                exit();
            }

            // No permitir cambiar el correo a uno que ya usa otra cuenta.
            if($this->userModel->correoEnUsoPorOtro($correo, $idUsuario)){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=email_dup');
                exit();
            }

            $fotoPerfilData = null;
            if(isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK){
                $extension  = strtolower(pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION));
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                if(!in_array($extension, $permitidos, true)){
                    header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=error'); exit();
                }

                $maxBytes = 2 * 1024 * 1024; // 2 MB
                if($_FILES['fotoPerfil']['size'] > $maxBytes){
                    header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=error'); exit();
                }

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeReal = finfo_file($finfo, $_FILES['fotoPerfil']['tmp_name']);
                finfo_close($finfo);
                if(!in_array($mimeReal, ['image/jpeg', 'image/png', 'image/webp'], true)){
                    header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=error'); exit();
                }

                $nombreFoto  = 'pfp_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $rutaDestino = __DIR__ . '/../assets/uploads/img_perfiles/' . $nombreFoto;
                if(move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $rutaDestino)){
                    $fotoPerfilData = $nombreFoto;
                }
            }

            $success = $this->userModel->updateUserProfileData(
                $idUsuario, $nombres, $apellidos, $correo, $telefono,
                $direccionDomicilio, $codigoPostal, $idDistrito, $fotoPerfilData
            );

            if($success){
                $_SESSION['nombres']      = $nombres;
                $_SESSION['emailUsuario'] = $correo;
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=success');
            }else{
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=error');
            }
            exit();
        }

        public function changePassword(){
            requireLogin();
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){ die('Método no permitido'); }
            verificarCsrf();

            $idUsuario       = $_SESSION['idUsuario'];
            $currentPassword = $_POST['currentPassword'] ?? '';
            $newPassword     = $_POST['newPassword'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            if($currentPassword === '' || $newPassword === '' || $confirmPassword === ''){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=empty'); exit();
            }
            if($newPassword !== $confirmPassword){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=mismatch'); exit();
            }
            if(!esPasswordValida($newPassword)){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=short'); exit();
            }

            // Se exige la contraseña actual: sin esto, quien se siente ante una sesión
            // abierta ajena podría cambiarla y quedarse con la cuenta.
            $usuario = $this->userModel->getUserById($idUsuario);
            if(!$usuario || !password_verify($currentPassword, $usuario['password'])){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=wrong'); exit();
            }

            // updatePassword invalida además el remember_token en la misma consulta.
            $success = $this->userModel->updatePassword($idUsuario, $newPassword);
            if($success){
                borrarCookieRecordarme();
                // Sesión nueva tras cambiar la contraseña: las sesiones anteriores mueren.
                regenerarSesion();
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=success');
            } else {
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showSeguridad&pass_status=error');
            }
            exit();
        }

        /* ============================================================
           RECUPERACIÓN DE CONTRASEÑA (token de un solo uso)

           El método anterior pedía correo + teléfono y cambiaba la contraseña en
           el acto. No servía: el teléfono se publica en los anuncios, así que los
           dos "secretos" estaban a la vista y cualquiera podía tomar cualquier
           cuenta. Ahora se envía un enlace con un token aleatorio que caduca.
           ============================================================ */

        public function showRecuperar(){
            iniciarSesion();
            global $base_path;
            require_once __DIR__ . '/../views/auth/recuperar_password.php';
        }

        /** Paso 1: el usuario pide el enlace indicando su correo. */
        public function solicitarReset(){
            iniciarSesion();
            verificarCsrf();

            $correo = normalizarCorreo($_POST['emailInput'] ?? '');

            if(!esCorreoValido($correo)){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showRecuperar&rec_status=email_invalido');
                exit();
            }

            $usuario = $this->userModel->getUserByEmail($correo);

            // Se responde SIEMPRE lo mismo, exista o no la cuenta. Si se dijera
            // "ese correo no está registrado", esta página se convertiría en un
            // buscador de cuentas válidas. Por eso el destino es fijo: ni siquiera
            // el modo desarrollo lo cambia, porque una URL distinta ya delataría
            // que la cuenta existe. El enlace de dev viaja aparte, en la sesión.
            $destino = BASE_URL . 'controllers/AuthController.php?action=showRecuperar&rec_status=enviado';

            if($usuario && ($usuario['estado'] ?? '') === 'Activo'){
                $tokenPlano = bin2hex(random_bytes(32));
                $tokenHash  = hash('sha256', $tokenPlano);

                $this->userModel->crearTokenReset($usuario['idUsuario'], $tokenHash, self::RESET_VALIDEZ_MINUTOS);

                $enlace = BASE_URL . 'controllers/AuthController.php?action=showResetForm&token=' . $tokenPlano;
                $mensaje = "Hola {$usuario['nombres']},\n\n"
                         . "Recibimos una solicitud para restablecer tu contraseña en Chamba Ya.\n"
                         . "Abre este enlace (caduca en " . self::RESET_VALIDEZ_MINUTOS . " minutos):\n\n"
                         . $enlace . "\n\n"
                         . "Si no fuiste tú, ignora este mensaje: tu contraseña no cambiará.";

                $enviado = enviarEmailNotificacion($usuario['correo'], 'Restablecer tu contraseña - Chamba Ya', $mensaje);

                // XAMPP no envía correos por defecto. En modo desarrollo se muestra el
                // enlace en pantalla para poder probar el flujo completo.
                // En producción (modo_dev = false) esto nunca se muestra.
                if(!$enviado && Database::modoDev()){
                    $_SESSION['reset_enlace_dev'] = $enlace;
                }
            }

            header('Location: ' . $destino);
            exit();
        }

        /** Paso 2: se abre el enlace y, si el token vale, se muestra el formulario. */
        public function showResetForm(){
            iniciarSesion();

            $token = $_GET['token'] ?? '';
            $reset = $token !== '' ? $this->userModel->obtenerResetValido(hash('sha256', $token)) : null;

            if(!$reset){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showRecuperar&rec_status=token_invalido');
                exit();
            }

            global $base_path;
            $tokenReset = $token; // lo usa la vista para reenviarlo en el POST
            require_once __DIR__ . '/../views/auth/reset_password.php';
        }

        /** Paso 3: se guarda la nueva contraseña y el token se quema. */
        public function resetPassword(){
            iniciarSesion();
            verificarCsrf();

            $token           = $_POST['token'] ?? '';
            $newPassword     = $_POST['newPassword'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            $volverAlForm = BASE_URL . 'controllers/AuthController.php?action=showResetForm&token=' . urlencode($token);

            if($newPassword !== $confirmPassword){
                header('Location: ' . $volverAlForm . '&rec_status=mismatch'); exit();
            }
            if(!esPasswordValida($newPassword)){
                header('Location: ' . $volverAlForm . '&rec_status=short'); exit();
            }

            // El token se vuelve a validar aquí: que el paso 2 lo aceptara no basta,
            // pudo caducar entretanto o llegar un POST directo sin pasar por el formulario.
            $reset = $token !== '' ? $this->userModel->obtenerResetValido(hash('sha256', $token)) : null;
            if(!$reset){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showRecuperar&rec_status=token_invalido');
                exit();
            }

            $ok = $this->userModel->updatePassword($reset['idUsuario'], $newPassword);
            if(!$ok){
                header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showRecuperar&rec_status=error');
                exit();
            }

            // Un enlace de recuperación sirve una sola vez.
            $this->userModel->marcarResetUsado($reset['idReset']);
            // Se limpia el bloqueo por intentos: el dueño legítimo recuperó su cuenta.
            $this->userModel->limpiarIntentosFallidos($reset['correo'], $this->ipCliente());
            borrarCookieRecordarme();

            header('Location: ' . BASE_URL . 'views/auth/login.php?login_status=pass_reset');
            exit();
        }
    }

    /* ================== Enrutador ================== */

    $controller = new AuthController();
    $action = $_GET['action'] ?? '';

    switch($action){
        case 'registerFirst':       $controller->registerFirst();       break;
        case 'showFormDatos':       $controller->showDatosForm();       break;
        case 'login':               $controller->login();               break;
        case 'completeRegister':    $controller->completeRegister();    break;
        case 'showMisDatos':        $controller->showMisDatos();        break;
        case 'showSeguridad':       $controller->showSeguridad();       break;
        case 'showPreferencias':    $controller->showPreferencias();    break;
        case 'guardarPreferencias': $controller->guardarPreferencias(); break;
        case 'desactivarCuenta':    $controller->desactivarCuenta();    break;
        case 'eliminarCuenta':      $controller->eliminarCuenta();      break;
        case 'updateMisDatos':      $controller->updateMisDatos();      break;
        case 'changePassword':      $controller->changePassword();      break;
        case 'showRecuperar':       $controller->showRecuperar();       break;
        case 'solicitarReset':      $controller->solicitarReset();      break;
        case 'showResetForm':       $controller->showResetForm();       break;
        case 'resetPassword':       $controller->resetPassword();       break;
        // Nombre antiguo del flujo inseguro: se redirige al nuevo para no dejar
        // enlaces rotos en marcadores o correos ya enviados.
        case 'recuperarPassword':   $controller->solicitarReset();      break;
        default:
            http_response_code(400);
            die('Acción no válida');
    }
