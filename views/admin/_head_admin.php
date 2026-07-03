<?php
    // Requiere: $seccionActiva, $tituloAdmin, $subtituloAdmin (opcional)
    require_once __DIR__ . '/../../core/config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <title><?= htmlspecialchars($tituloAdmin ?? 'Panel') ?> - Admin Chamba Ya</title>
    <?php require_once __DIR__ . '/../../assets/css/style_admin.php'; ?>
</head>
<body class="admin-body">
    <?php require __DIR__ . '/_sidebar_admin.php'; ?>
    <main class="admin-main">
        <button class="admin-menu-toggle" onclick="document.getElementById('adminSidebar').classList.toggle('abierto')">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="admin-header">
            <h1><?= htmlspecialchars($tituloAdmin ?? '') ?></h1>
            <?php if (!empty($subtituloAdmin)): ?><p><?= htmlspecialchars($subtituloAdmin) ?></p><?php endif; ?>
        </div>

        <?php
            // Mensajes de estado (?estado=...)
            if (!empty($_GET['estado'])):
                $adminMsgs = [
                    'estado_ok'            => ['Estado actualizado correctamente.', 'ok'],
                    'rol_ok'               => ['Rol actualizado correctamente.', 'ok'],
                    'usuario_eliminado'    => ['Usuario eliminado.', 'ok'],
                    'reporte_resuelto'     => ['Reporte marcado como revisado.', 'ok'],
                    'reporte_descartado'   => ['Reporte descartado.', 'ok'],
                    'anuncio_eliminado'    => ['Anuncio eliminado.', 'ok'],
                    'categoria_creada'     => ['Categoría creada.', 'ok'],
                    'categoria_editada'    => ['Categoría actualizada.', 'ok'],
                    'categoria_eliminada'  => ['Categoría eliminada.', 'ok'],
                    'no_self'              => ['No puedes realizar esta acción sobre tu propia cuenta.', 'warn'],
                    'no_admin'             => ['No puedes eliminar a otro administrador.', 'warn'],
                    'categoria_con_anuncios' => ['No se puede eliminar: la categoría tiene anuncios asociados.', 'warn'],
                    'error'                => ['Ocurrió un error. Inténtalo de nuevo.', 'err'],
                ];
                $m = $adminMsgs[$_GET['estado']] ?? null;
                if ($m):
            ?>
                <div class="admin-alert <?= $m[1] ?>"><?= htmlspecialchars($m[0]) ?></div>
        <?php endif; endif; ?>
