<?php
    $tituloAdmin = 'Categorías';
    $subtituloAdmin = count($categorias ?? []) . ' categorías';
    require __DIR__ . '/_head_admin.php';
?>

<div class="admin-toolbar">
    <button data-abre-modal="modalCrearCategoria"><i class="fa-solid fa-plus"></i> Nueva categoría</button>
</div>

<?php if (empty($categorias)): ?>
    <div class="admin-table-wrap"><div class="admin-vacio"><i class="fa-solid fa-tags"></i>No hay categorías todavía.</div></div>
<?php else: ?>
    <div class="cat-grid">
        <?php foreach ($categorias as $c): ?>
            <?php
                $img = !empty($c['imagen']) ? $c['imagen'] : 'servicios_varios.png';
                $tieneAnuncios = (int) $c['total_anuncios'] > 0;
            ?>
            <div class="cat-card">
                <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($img) ?>" onerror="this.src='<?= BASE_URL ?>assets/img/servicios_varios.png';" alt="">
                <h4><?= htmlspecialchars($c['nombre']) ?></h4>
                <div class="cat-count"><?= (int) $c['total_anuncios'] ?> anuncio(s)</div>
                <div class="cat-acciones">
                    <button class="btn-accion" data-abre-modal="modalEditarCategoria"
                            onclick="prepararEditar(<?= (int) $c['idCategoria'] ?>, '<?= htmlspecialchars(addslashes($c['nombre'])) ?>')">
                        <i class="fa-solid fa-pen"></i> Editar
                    </button>
                    <?php if (!$tieneAnuncios): ?>
                        <button class="btn-accion peligro" data-abre-modal="modalEliminarCategoria"
                                onclick="document.querySelector('#modalEliminarCategoria [name=idCategoria]').value=<?= (int) $c['idCategoria'] ?>; document.getElementById('nombreEliminarCat').textContent='<?= htmlspecialchars(addslashes($c['nombre'])) ?>'">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    <?php else: ?>
                        <button class="btn-accion" disabled title="Tiene anuncios asociados" style="opacity:.5;cursor:not-allowed;">
                            <i class="fa-solid fa-lock"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Modal crear -->
<div class="admin-modal-overlay" id="modalCrearCategoria">
    <div class="admin-modal">
        <h3>Nueva categoría</h3>
        <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=crearCategoria" enctype="multipart/form-data">
            <label>Nombre</label>
            <input type="text" name="nombre" required placeholder="Ej: Plomería">
            <label>Imagen (opcional, JPG/PNG/WEBP)</label>
            <input type="file" name="imagen" accept="image/*">
            <div class="admin-modal-acciones">
                <button type="button" class="btn-modal cancelar" data-cierra-modal>Cancelar</button>
                <button type="submit" class="btn-modal confirmar">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal editar -->
<div class="admin-modal-overlay" id="modalEditarCategoria">
    <div class="admin-modal">
        <h3>Editar categoría</h3>
        <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=editarCategoria" enctype="multipart/form-data">
            <input type="hidden" name="idCategoria" value="">
            <label>Nombre</label>
            <input type="text" name="nombre" required>
            <label>Nueva imagen (opcional — deja vacío para mantener la actual)</label>
            <input type="file" name="imagen" accept="image/*">
            <div class="admin-modal-acciones">
                <button type="button" class="btn-modal cancelar" data-cierra-modal>Cancelar</button>
                <button type="submit" class="btn-modal confirmar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal eliminar -->
<div class="admin-modal-overlay" id="modalEliminarCategoria">
    <div class="admin-modal">
        <h3>Eliminar categoría</h3>
        <p>¿Eliminar la categoría "<strong id="nombreEliminarCat"></strong>"?</p>
        <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=eliminarCategoria">
            <input type="hidden" name="idCategoria" value="">
            <div class="admin-modal-acciones">
                <button type="button" class="btn-modal cancelar" data-cierra-modal>Cancelar</button>
                <button type="submit" class="btn-modal confirmar peligro">Eliminar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function prepararEditar(id, nombre) {
        var m = document.getElementById('modalEditarCategoria');
        m.querySelector('[name=idCategoria]').value = id;
        m.querySelector('[name=nombre]').value = nombre;
    }
</script>

<?php require __DIR__ . '/_foot_admin.php'; ?>
