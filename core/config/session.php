<?php
/**
 * Manejo de sesiones.
 *
 * Se endurece en tres frentes:
 *  1. La cookie de sesión se marca httponly y samesite (y secure bajo HTTPS).
 *  2. El id de sesión se regenera al iniciar sesión (evita "session fixation").
 *  3. Las sesiones inactivas caducan solas.
 */

// IMPORTANTE: este archivo NO incluye config.php.
//
// config.php incluye csrf.php, que incluye este archivo, y al final arranca la
// sesión. Si aquí se volviera a incluir config.php se formaría un ciclo: cuando
// el primer archivo cargado es session.php, config.php se ejecutaría desde dentro
// y llamaría a iniciarSesion() antes de que este archivo terminara de definir sus
// constantes (PHP declara las funciones al compilar, pero las constantes se
// ejecutan en orden), provocando un error fatal.
//
// Las dos funciones que necesitan BASE_URL la cargan por su cuenta más abajo.

// Caduca la sesión tras 2 horas sin actividad.
const SESION_INACTIVIDAD_SEGUNDOS = 7200;

function iniciarSesion() {
    if (session_status() === PHP_SESSION_NONE) {
        configurarCookieSesion();
        session_start();
    }
    // Si la sesión lleva demasiado tiempo inactiva, se cierra.
    if (sesionExpirada()) {
        cerrarSesion();
        return;
    }
    $_SESSION['ultima_actividad'] = time();

    // Si no hay sesión pero sí una cookie "recordarme" válida, loguea automático.
    intentarAutoLoginPorCookie();
}

/**
 * Configura la cookie de sesión ANTES de session_start (después ya no tiene efecto).
 *  - httponly: JavaScript no puede leerla, así un XSS no roba la sesión.
 *  - samesite=Lax: el navegador no la envía en peticiones POST desde otros sitios,
 *    lo que corta buena parte de los ataques CSRF incluso antes del token.
 *  - secure: solo se activa bajo HTTPS (en XAMPP local es HTTP y romperia el login).
 */
function configurarCookieSesion(): void {
    $esHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? '') == 443)
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    session_set_cookie_params([
        'lifetime' => 0,          // la cookie muere al cerrar el navegador
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => $esHttps,
    ]);
    // El id de sesión solo puede venir de la cookie, nunca de la URL.
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_strict_mode', '1'); // rechaza ids de sesión inventados por el cliente
}

function sesionExpirada(): bool {
    if (!isset($_SESSION['ultima_actividad'])) {
        return false;
    }
    return (time() - (int) $_SESSION['ultima_actividad']) > SESION_INACTIVIDAD_SEGUNDOS;
}

/**
 * Regenera el id de sesión conservando los datos.
 * Se llama tras iniciar sesión, registrarse y cambiar la contraseña: si un atacante
 * había fijado un id de sesión antes, ese id deja de servir en el momento del login.
 */
function regenerarSesion(): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true); // true = borra el fichero de la sesión vieja
    }
}

/** Cierra la sesión por completo: datos, fichero y cookie. */
function cerrarSesion(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires'  => time() - 42000,
            'path'     => $p['path'],
            'domain'   => $p['domain'],
            'secure'   => $p['secure'],
            'httponly' => $p['httponly'],
            'samesite' => $p['samesite'] ?? 'Lax',
        ]);
    }
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}

/** Borra la cookie "recordarme" del navegador con los mismos flags con que se creó. */
function borrarCookieRecordarme(): void {
    setcookie('remember_token', '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function intentarAutoLoginPorCookie(): void {
    // Ya hay sesión, o no hay cookie: no hacemos nada.
    if (isset($_SESSION['idUsuario']) || empty($_COOKIE['remember_token'])) {
        return;
    }

    require_once __DIR__ . '/../db/database.php';
    require_once __DIR__ . '/../../models/userModel.php';

    $tokenHash = hash('sha256', $_COOKIE['remember_token']);
    $userModel = new UserModel();
    $usuario   = $userModel->getUserByRememberToken($tokenHash);

    // Token inválido, vencido, o cuenta desactivada: se borra la cookie.
    if (!$usuario || ($usuario['estado'] ?? '') !== 'Activo') {
        if ($usuario) {
            $userModel->limpiarRememberToken($usuario['idUsuario']);
        }
        borrarCookieRecordarme();
        return;
    }

    // El id de sesión se regenera también aquí: la sesión pasa de anónima a autenticada.
    regenerarSesion();

    $_SESSION['idUsuario']    = $usuario['idUsuario'];
    $_SESSION['nombres']      = $usuario['nombres'];
    $_SESSION['emailUsuario'] = $usuario['correo'];
    // Restaura el rol para que un admin con "recordarme" no pierda el panel.
    $_SESSION['rol']          = $usuario['rol'] ?? 'usuario';

    // Rotación del token: cada uso genera uno nuevo y anula el anterior.
    // Si alguien robó la cookie, deja de servir en cuanto el dueño legítimo entra.
    rotarRememberToken($userModel, (int) $usuario['idUsuario']);
}

/** Sustituye el remember_token por uno nuevo y actualiza la cookie. */
function rotarRememberToken(UserModel $userModel, int $idUsuario): void {
    $tokenPlano = bin2hex(random_bytes(32));
    $userModel->guardarRememberToken($idUsuario, hash('sha256', $tokenPlano), 30);

    setcookie('remember_token', $tokenPlano, [
        'expires'  => strtotime('+30 days'),
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    ]);
}

// 0 = nadie logueado.
function obtenerIdUsuarioActivo(): int {
    iniciarSesion();
    if (!isset($_SESSION['idUsuario'])) {
        return 0;
    }
    return (int) $_SESSION['idUsuario'];
}

/** Guard para páginas que exigen sesión iniciada. */
function requireLogin(): void {
    require_once __DIR__ . '/config.php'; // aquí sí: se necesita BASE_URL
    iniciarSesion();
    if (!isset($_SESSION['idUsuario'])) {
        header('Location: ' . BASE_URL . 'views/auth/login.php');
        exit();
    }
}

// ¿El usuario logueado es administrador?
function esAdmin(): bool {
    iniciarSesion();
    return isset($_SESSION['idUsuario']) && ($_SESSION['rol'] ?? 'usuario') === 'admin';
}

// Guard para las páginas/acciones del panel: corta si no es admin.
function requireAdmin(): void {
    require_once __DIR__ . '/config.php'; // aquí sí: se necesita BASE_URL
    iniciarSesion();
    if (!isset($_SESSION['idUsuario'])) {
        // No logueado -> al login
        header('Location: ' . BASE_URL . 'views/auth/login.php');
        exit();
    }
    if (($_SESSION['rol'] ?? 'usuario') !== 'admin') {
        // Logueado pero no admin -> al inicio
        header('Location: ' . BASE_URL . 'index.php');
        exit();
    }
}
