<?php
    // Requiere: $pagina, $totalPaginas, $seccionActiva. Preserva los filtros actuales de $_GET.
    if (($totalPaginas ?? 1) <= 1) return;
    $qs = $_GET;
    unset($qs['pagina']);
    $base = BASE_URL . 'controllers/AdminController.php?' . http_build_query(array_merge($qs, ['action' => $seccionActiva]));
    $link = fn($p) => $base . '&pagina=' . $p;
?>
<div class="admin-paginacion">
    <?php if ($pagina > 1): ?>
        <a href="<?= htmlspecialchars($link($pagina - 1)) ?>">&laquo; Anterior</a>
    <?php endif; ?>

    <?php
        $desde = max(1, $pagina - 2);
        $hasta = min($totalPaginas, $pagina + 2);
        for ($p = $desde; $p <= $hasta; $p++):
    ?>
        <?php if ($p == $pagina): ?>
            <span class="actual"><?= $p ?></span>
        <?php else: ?>
            <a href="<?= htmlspecialchars($link($p)) ?>"><?= $p ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($pagina < $totalPaginas): ?>
        <a href="<?= htmlspecialchars($link($pagina + 1)) ?>">Siguiente &raquo;</a>
    <?php endif; ?>
</div>
