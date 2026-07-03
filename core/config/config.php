<?php
    $documentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
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
?>
