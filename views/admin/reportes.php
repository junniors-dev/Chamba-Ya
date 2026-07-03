<?php
    $tituloAdmin = 'Reportes';
    $subtituloAdmin = ($totalReportes ?? 0) . ' reportes en total';
    require __DIR__ . '/_head_admin.php';
    $filtro = $_GET['estado'] ?? '';
    $tabs = ['' => 'Todos', 'Pendiente' => 'Pendientes', 'Revisado' => 'Revisados', 'Descartado' => 'Descartados'];
    $badgeEstado = ['Pendiente' => 'badge-amarillo', 'Revisado' => 'badge-verde', 'Descartado' => 'badge-gris'];
?>

<div class="admin-tabs">
    <?php foreach ($tabs as $val => $txt): ?>
        <a href="<?= BASE_URL ?>controllers/AdminController.php?action=reportes<?= $val ? '&estado=' . $val : '' ?>"
           class="<?= $filtro === $val ? 'activo' : '' ?>"><?= $txt ?></a>
    <?php endforeach; ?>
</div>

<div class="admin-table-wrap">
    <?php if (empty($reportes)): ?>
        <div class="admin-vacio"><i class="fa-solid fa-flag"></i>No hay reportes en esta categoría.</div>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th><th>Reportante</th><th>Reportado</th><th>Anuncio</th>
                    <th>Motivo</th><th>Fecha</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportes as $r): ?>
                    <tr>
                        <td><?= (int) $r['idReporte'] ?></td>
                        <td><?= htmlspecialchars(trim(($r['reporta_nombres'] ?? '') . ' ' . ($r['reporta_apellidos'] ?? '')) ?: '—') ?></td>
                        <td><?= htmlspecialchars(trim(($r['reportado_nombres'] ?? '') . ' ' . ($r['reportado_apellidos'] ?? '')) ?: '—') ?></td>
                        <td>
                            <?php if (!empty($r['anuncio_titulo'])): ?>
                                <a href="<?= BASE_URL ?>index.php?action=detalle-anuncio&id=<?= (int) $r['idAnuncio'] ?>" target="_blank" style="color:#60a5fa;text-decoration:none;">
                                    <?= htmlspecialchars(mb_strimwidth($r['anuncio_titulo'], 0, 30, '…')) ?>
                                </a>
                            <?php else: ?>
                                <span style="color:#64748b;">(eliminado)</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($r['motivo']) ?></td>
                        <td><?= date('d/m/Y', strtotime($r['fecha'])) ?></td>
                        <td><span class="badge <?= $badgeEstado[$r['estado']] ?? 'badge-gris' ?>"><?= htmlspecialchars($r['estado']) ?></span></td>
                        <td>
                            <div class="acciones">
                                <button class="btn-accion" data-abre-modal="modalDetalleReporte"
                                        onclick="verDetalleReporte(<?= (int) $r['idReporte'] ?>, '<?= htmlspecialchars(addslashes($r['motivo'])) ?>', '<?= htmlspecialchars(addslashes($r['detalle'] ?? '')) ?>')">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <?php if ($r['estado'] === 'Pendiente'): ?>
                                    <button class="btn-accion exito" data-abre-modal="modalResolver"
                                            onclick="document.querySelector('#modalResolver [name=idReporte]').value=<?= (int) $r['idReporte'] ?>">
                                        <i class="fa-solid fa-check"></i> Resolver
                                    </button>
                                    <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=descartarReporte" style="display:inline;">
                                        <input type="hidden" name="idReporte" value="<?= (int) $r['idReporte'] ?>">
                                        <button class="btn-accion" type="submit"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                    <?php if (!empty($r['anuncio_titulo'])): ?>
                                        <button class="btn-accion peligro" data-abre-modal="modalEliminarReportado"
                                                onclick="document.querySelector('#modalEliminarReportado [name=idReporte]').value=<?= (int) $r['idReporte'] ?>">
                                            <i class="fa-solid fa-trash"></i> Anuncio
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/_paginacion_admin.php'; ?>

<!-- Modal detalle -->
<div class="admin-modal-overlay" id="modalDetalleReporte">
    <div class="admin-modal">
        <h3>Detalle del reporte</h3>
        <p><strong>Motivo:</strong> <span id="detMotivo"></span></p>
        <p><strong>Descripción:</strong><br><span id="detDetalle"></span></p>
        <div class="admin-modal-acciones">
            <button type="button" class="btn-modal cancelar" data-cierra-modal>Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal resolver -->
<div class="admin-modal-overlay" id="modalResolver">
    <div class="admin-modal">
        <h3>Resolver reporte</h3>
        <p>Marca este reporte como revisado. Puedes dejar una nota interna.</p>
        <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=resolverReporte">
            <input type="hidden" name="idReporte" value="">
            <label>Nota (opcional)</label>
            <textarea name="notas" rows="3" placeholder="Ej: Contenido verificado, sin infracción."></textarea>
            <div class="admin-modal-acciones">
                <button type="button" class="btn-modal cancelar" data-cierra-modal>Cancelar</button>
                <button type="submit" class="btn-modal confirmar">Marcar revisado</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal eliminar anuncio reportado -->
<div class="admin-modal-overlay" id="modalEliminarReportado">
    <div class="admin-modal">
        <h3>Eliminar anuncio reportado</h3>
        <p>Se eliminará el anuncio y todos sus datos asociados, y el reporte quedará cerrado. Esta acción no se puede deshacer.</p>
        <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=eliminarAnuncioReportado">
            <input type="hidden" name="idReporte" value="">
            <div class="admin-modal-acciones">
                <button type="button" class="btn-modal cancelar" data-cierra-modal>Cancelar</button>
                <button type="submit" class="btn-modal confirmar peligro">Eliminar anuncio</button>
            </div>
        </form>
    </div>
</div>

<script>
    function verDetalleReporte(id, motivo, detalle) {
        document.getElementById('detMotivo').textContent = motivo;
        document.getElementById('detDetalle').textContent = detalle || '(sin descripción)';
    }
</script>

<?php require __DIR__ . '/_foot_admin.php'; ?>
