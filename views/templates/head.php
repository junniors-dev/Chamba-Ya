<?php
    // Esta plantilla imprime el token CSRF y usa BASE_URL, así que se asegura de
    // que config.php esté cargado (él trae los helpers de seguridad). Las vistas
    // que solo incluyen session.php no lo tendrían, y aquí fallaría a medio render.
    require_once __DIR__ . '/../../core/config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Chamba Ya' ?></title>
    <!-- Token CSRF para las peticiones AJAX (favoritos, notificaciones) -->
    <meta name="csrf-token" content="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
    <script>const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;</script>
</head>