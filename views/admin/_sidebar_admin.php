<?php
    // Requiere: $seccionActiva (string). Calcula el badge de reportes pendientes.
    if (!isset($seccionActiva)) $seccionActiva = '';
    $adminNombre = htmlspecialchars($_SESSION['nombres'] ?? 'Admin');
    if (!isset($reportesPendientesBadge)) {
        require_once __DIR__ . '/../../models/AdminModel.php';
        $reportesPendientesBadge = (new AdminModel())->contarReportes('Pendiente');
    }
    $adminBase = BASE_URL . 'controllers/AdminController.php?action=';
    $items = [
        'dashboard'  => ['Dashboard', 'fa-gauge-high'],
        'usuarios'   => ['Usuarios',  'fa-users'],
        'reportes'   => ['Reportes',  'fa-flag'],
        'anuncios'   => ['Anuncios',  'fa-bullhorn'],
        'categorias' => ['Categorías','fa-tags'],
    ];
?>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-logo">
        <a href="<?= BASE_URL ?>index.php"><img src="<?= BASE_URL ?>assets/img/logo-chamba-ya.png" alt="Chamba Ya"></a>
        <span>Panel de Administración</span>
    </div>

    <ul class="admin-nav">
        <?php foreach ($items as $key => $item): ?>
            <li>
                <a href="<?= $adminBase . $key ?>" class="<?= $seccionActiva === $key ? 'activo' : '' ?>">
                    <i class="fa-solid <?= $item[1] ?>"></i> <?= $item[0] ?>
                    <?php if ($key === 'reportes' && $reportesPendientesBadge > 0): ?>
                        <span class="badge-pendiente"><?= $reportesPendientesBadge > 9 ? '9+' : $reportesPendientesBadge ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="admin-sidebar-footer">
        <a href="<?= BASE_URL ?>index.php"><i class="fa-solid fa-arrow-left"></i> Volver al sitio</a>
        <div class="admin-name"><i class="fa-solid fa-user-shield"></i> <?= $adminNombre ?></div>
    </div>
</aside>
