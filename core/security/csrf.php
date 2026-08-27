<?php
/**
 * Protección CSRF (Cross-Site Request Forgery).
 *
 * Un ataque CSRF consiste en que una web externa envíe un formulario a Chamba Ya
 * usando la sesión que el usuario ya tiene abierta. El navegador adjunta la cookie
 * automáticamente, así que sin defensa el servidor no puede distinguir esa petición
 * de una legítima.
 *
 * La defensa: un token secreto guardado en la sesión que se incrusta en cada
 * formulario. La web atacante no puede leerlo (la política del mismo origen se lo
 * impide), así que no puede falsificar la petición.
 */

require_once __DIR__ . '/../config/session.php';

/** Devuelve el token de la sesión, creándolo la primera vez. */
function csrfToken(): string {
    iniciarSesion();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Imprime el <input hidden> que va dentro de cada formulario POST. */
function campoCsrf(): string {
    return '<input type="hidden" name="csrf_token" value="'
         . htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Compara el token recibido con el de la sesión.
 * Usa hash_equals y no === porque compara en tiempo constante: una comparación
 * normal se corta en el primer carácter distinto y eso filtra información sobre
 * el token a través del tiempo de respuesta.
 */
function csrfValido($token): bool {
    iniciarSesion();
    if (empty($_SESSION['csrf_token']) || !is_string($token) || $token === '') {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Guard para los controladores: corta la petición POST si el token no es válido.
 * Se llama al inicio de toda acción que modifique datos.
 */
function verificarCsrf(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return;
    }

    // El token puede llegar por el campo oculto del formulario o por la cabecera
    // X-CSRF-Token, que es como lo envían las peticiones fetch/AJAX.
    $token = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

    if (!csrfValido($token)) {
        http_response_code(419); // 419 = token expirado (convención de Laravel)

        // Si la petición es AJAX se responde en JSON, porque el JavaScript que la
        // hizo espera JSON y con HTML plano fallaría al parsear.
        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
            header('Content-Type: application/json; charset=utf-8');
            exit(json_encode(['ok' => false, 'estado' => 'csrf_invalido']));
        }
        exit('Sesión expirada o petición no autorizada. Vuelve atrás y recarga la página.');
    }
}

/** Rota el token. Se llama tras cerrar sesión para no reutilizar el anterior. */
function rotarCsrfToken(): void {
    iniciarSesion();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
