<?php
    $tituloAdmin = 'Anuncios';
    $subtituloAdmin = ($totalAnuncios ?? 0) . ' anuncios en total';
    require __DIR__ . '/_head_admin.php';
    $estadosAnuncio = ['Disponible', 'En proceso', 'Finalizado', 'Cancelado'];
    $badgeAnuncio = ['Disponible' => 'badge-verde', 'En proceso' => 'badge-azul', 'Finalizado' => 'badge-gris', 'Cancelado' => 'badge-rojo'];
?>

<form class="admin-toolbar" method="GET" action="<?= BASE_URL ?>controllers/AdminController.php">
    <input type="hidden" name="action" value="anuncios">
    <input type="text" name="buscar" placeholder="Buscar por título..." value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
    <select name="tipo">
        <option value="">Todos los tipos</option>
        <option value="Trabajo"  <?= ($_GET['tipo'] ?? '') === 'Trabajo' ? 'selected' : '' ?>>Trabajo</option>
        <option value="Servicio" <?= ($_GET['tipo'] ?? '') === 'Servicio' ? 'selected' : '' ?>>Servicio</option>
    </select>
    <select name="estado">
        <option value="">Todos los estados</option>
        <?php foreach ($estadosAnuncio as $e): ?>
            <option value="<?= $e ?>" <?= ($_GET['estado'] ?? '') === $e ? 'selected' : '' ?>><?= $e ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
    <?php if (!empty($_GET['buscar']) || !empty($_GET['tipo']) || !empty($_GET['estado'])): ?>
        <a class="limpiar" href="<?= BASE_URL ?>controllers/AdminController.php?action=anuncios">Limpiar</a>
    <?php endif; ?>
</form>

<div class="admin-table-wrap">
    <?php if (empty($anuncios)): ?>
        <div class="admin-vacio"><i class="fa-solid fa-bullhorn"></i>No se encontraron anuncios.</div>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th><th>Título</th><th>Tipo</th><th>Autor</th>
                    <th>Estado</th><th>Fecha</th><th>Vistas</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($anuncios as $a): ?>
                    <?php $tipoUrl = strtolower($a['tipoAnuncio']); ?>
                    <tr>
                        <td><?= (int) $a['idAnuncio'] ?></td>
                        <td><?= htmlspecialchars(mb_strimwidth($a['titulo'], 0, 40, '…')) ?></td>
                        <td><span class="badge <?= $a['tipoAnuncio'] === 'Trabajo' ? 'badge-naranja' : 'badge-azul' ?>"><?= htmlspecialchars($a['tipoAnuncio']) ?></span></td>
                        <td><?= htmlspecialchars($a['nombres'] . ' ' . $a['apellidos']) ?></td>
                        <td><span class="badge <?= $badgeAnuncio[$a['estado']] ?? 'badge-gris' ?>"><?= htmlspecialchars($a['estado']) ?></span></td>
                        <td><?= date('d/m/Y', strtotime($a['fechaPublicacion'])) ?></td>
                        <td><i class="fa-regular fa-eye"></i> <?= (int) $a['vistas'] ?></td>
                        <td>
                            <div class="acciones">
                                <a class="btn-accion" href="<?= BASE_URL ?>index.php?action=detalle-anuncio&id=<?= (int) $a['idAnuncio'] ?>&tipo=<?= $tipoUrl ?>" target="_blank">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=cambiarEstadoAnuncio" style="display:inline;">
                    <?= campoCsrf() ?>
                                    <input type="hidden" name="idAnuncio" value="<?= (int) $a['idAnuncio'] ?>">
                                    <select name="estado" onchange="this.form.submit()" class="btn-accion" style="cursor:pointer;">
                                        <?php foreach ($estadosAnuncio as $e): ?>
                                            <option value="<?= $e ?>" <?= $a['estado'] === $e ? 'selected' : '' ?>><?= $e ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                                <button class="btn-accion peligro" data-abre-modal="modalEliminarAnuncio"
                                        onclick="document.querySelector('#modalEliminarAnuncio [name=idAnuncio]').value=<?= (int) $a['idAnuncio'] ?>; document.getElementById('tituloEliminarAnuncio').textContent='<?= htmlspecialchars(addslashes(mb_strimwidth($a['titulo'],0,40,'…'))) ?>'">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/_paginacion_admin.php'; ?>

<!-- Modal eliminar anuncio -->
<div class="admin-modal-overlay" id="modalEliminarAnuncio">
    <div class="admin-modal">
        <h3>Eliminar anuncio</h3>
        <p>¿Eliminar "<strong id="tituloEliminarAnuncio"></strong>"? Se borrarán sus postulaciones, favoritos y reportes. No se puede deshacer.</p>
        <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=eliminarAnuncio">
                    <?= campoCsrf() ?>
            <input type="hidden" name="idAnuncio" value="">
            <div class="admin-modal-acciones">
                <button type="button" class="btn-modal cancelar" data-cierra-modal>Cancelar</button>
                <button type="submit" class="btn-modal confirmar peligro">Eliminar</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/_foot_admin.php'; ?>
