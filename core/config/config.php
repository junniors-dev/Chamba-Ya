<?php
    // Zona horaria del proyecto. Sin esto PHP usa la del servidor (aquí venía en
    // Europe/Berlin) mientras MySQL usa la suya: había 7 horas de diferencia, y
    // cualquier fecha escrita por PHP y comparada por MySQL salía desplazada.
    date_default_timezone_set('America/Lima');

    $documentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    $projectRoot = str_replace('\\', '/', dirname(dirname(__DIR__)));

    // Calcula el nombre de la carpeta del proyecto automáticamente
    $folderName = str_replace($documentRoot, '', $projectRoot);

    // Define el base_path (Ejemplo: /Chamba-Ya-main/)
    define('BASE_URL', $folderName . '/');

    // Si no hay monto, muestra "A convenir".
    if (!function_exists('formatearPago')) {
        function formatearPago($monto): string {
            if ($monto === null || $monto === '' || (float) $monto <= 0) {
                return 'A convenir';
            }
            return 'S/. ' . number_format((float) $monto, 2);
        }
    }

    // Arma el link de WhatsApp (wa.me) a partir de un teléfono. Null si no hay.
    if (!function_exists('linkWhatsApp')) {
        function linkWhatsApp($telefono, $mensaje = ''): ?string {
            $num = preg_replace('/\D+/', '', (string) $telefono);
            if ($num === '') return null;
            if (strlen($num) === 9) $num = '51' . $num; // celular peruano sin código de país
            $url = 'https://wa.me/' . $num;
            if ($mensaje !== '') $url .= '?text=' . rawurlencode($mensaje);
            return $url;
        }
    }

    // Link de WhatsApp para COMPARTIR (sin número fijo): abre el selector de contactos.
    if (!function_exists('linkCompartirWhatsApp')) {
        function linkCompartirWhatsApp(string $mensaje): string {
            return 'https://wa.me/?text=' . rawurlencode($mensaje);
        }
    }

    // Se cargan al final, cuando BASE_URL ya existe: asi cualquier vista que
    // incluya config.php dispone de campoCsrf(), verificarCsrf() y los
    // validadores sin tener que requerirlos una por una.
    require_once __DIR__ . '/../security/csrf.php';
    require_once __DIR__ . '/../security/validacion.php';

    // La sesión se inicia aquí, y no dentro de cada vista.
    //
    // session_start() tiene que ejecutarse ANTES de la primera salida: envía la
    // cookie de sesión en las cabeceras, y una vez que se ha impreso HTML las
    // cabeceras ya viajaron. Las vistas sueltas (login.php, recuperar_password.php)
    // incluyen config.php en su primera línea, antes de imprimir nada, así que
    // este es el único punto donde el arranque es seguro para todas.
    //
    // La comprobación function_exists evita un fallo de orden de carga: si el
    // primer archivo incluido es session.php, este config.php se ejecuta desde
    // su interior y sus funciones aún no están definidas. En ese caso quien
    // incluyó session.php ya llama a iniciarSesion() por su cuenta.
    if (function_exists('iniciarSesion')) {
        iniciarSesion();
    }
?>
