<?php
/**
 * Validación y saneamiento de entradas.
 *
 * Regla de oro: nunca confiar en lo que llega del cliente. El HTML puede pedir
 * type="email" o maxlength, pero eso es solo una ayuda visual: cualquiera puede
 * enviar un POST directo saltándose el formulario. La validación real va aquí,
 * en el servidor.
 */

/** Correo con formato válido y que cabe en la columna (varchar(80)). */
function esCorreoValido($correo): bool {
    $correo = trim((string) $correo);
    if ($correo === '' || strlen($correo) > 80) {
        return false;
    }
    return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false;
}

/** Normaliza el correo: sin espacios y en minúsculas, para que no se dupliquen cuentas. */
function normalizarCorreo($correo): string {
    return strtolower(trim((string) $correo));
}

/** Teléfono peruano: 9 dígitos. Se aceptan espacios y guiones, se limpian aquí. */
function esTelefonoValido($telefono): bool {
    $limpio = preg_replace('/\D+/', '', (string) $telefono);
    return $limpio !== null && strlen($limpio) === 9;
}

function normalizarTelefono($telefono): string {
    return (string) preg_replace('/\D+/', '', (string) $telefono);
}

/**
 * Limpia un texto libre: quita espacios sobrantes, elimina caracteres de control
 * y lo recorta al máximo de la columna para que la BD no rechace el INSERT.
 */
function limpiarTexto($texto, int $maxLongitud = 255): string {
    $texto = trim((string) $texto);
    $texto = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $texto);
    if (function_exists('mb_substr')) {
        return mb_substr($texto, 0, $maxLongitud, 'UTF-8');
    }
    return substr($texto, 0, $maxLongitud);
}

/** Longitud contada en caracteres (no en bytes: una "ñ" ocupa 2 bytes en UTF-8). */
function longitud($texto): int {
    return function_exists('mb_strlen')
        ? mb_strlen((string) $texto, 'UTF-8')
        : strlen((string) $texto);
}

/**
 * Reglas de contraseña: mínimo 8 caracteres y máximo 72.
 * El máximo no es capricho: bcrypt trunca silenciosamente a partir de 72 bytes,
 * así que aceptar más daría una falsa sensación de seguridad.
 */
function esPasswordValida($password): bool {
    $largo = strlen((string) $password); // en bytes, que es lo que mide bcrypt
    return $largo >= 8 && $largo <= 72;
}

/** Atajo para escapar en las vistas. */
function e($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
