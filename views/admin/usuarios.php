<?php
    $tituloAdmin = 'Usuarios';
    $subtituloAdmin = ($totalUsuarios ?? 0) . ' usuarios registrados';
    require __DIR__ . '/_head_admin.php';
    $idAdminActual = (int) ($_SESSION['idUsuario'] ?? 0);
    $estadosUsuario = ['Activo', 'Inactivo', 'Suspendido', 'Bloqueado'];
    $badgeUsuario = ['Activo' => 'badge-verde', 'Inactivo' => 'badge-gris', 'Suspendido' => 'badge-amarillo', 'Bloqueado' => 'badge-rojo'];
?>

<form class="admin-toolbar" method="GET" action="<?= BASE_URL ?>controllers/AdminController.php">
    <input type="hidden" name="action" value="usuarios">
    <input type="text" name="buscar" placeholder="Buscar por nombre o correo..." value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
    <select name="estado">
        <option value="">Todos los estados</option>
        <?php foreach (['Activo','Inactivo','Suspendido','Bloqueado'] as $e): ?>
            <option value="<?= $e ?>" <?= ($_GET['estado'] ?? '') === $e ? 'selected' : '' ?>><?= $e ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
    <?php if (!empty($_GET['buscar']) || !empty($_GET['estado'])): ?>
        <a class="limpiar" href="<?= BASE_URL ?>controllers/AdminController.php?action=usuarios">Limpiar</a>
    <?php endif; ?>
</form>

<div class="admin-table-wrap">
    <?php if (empty($usuarios)): ?>
        <div class="admin-vacio"><i class="fa-solid fa-users-slash"></i>No se encontraron usuarios.</div>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th><th>Usuario</th><th>Correo</th><th>Distrito</th>
                    <th>Registro</th><th>Rol</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <?php $foto = !empty($u['fotoPerfil']) ? $u['fotoPerfil'] : 'default.png'; ?>
                    <tr>
                        <td><?= (int) $u['idUsuario'] ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <img class="avatar-mini" src="<?= BASE_URL ?>assets/uploads/img_perfiles/<?= htmlspecialchars($foto) ?>"
                                     onerror="this.src='<?= BASE_URL ?>assets/uploads/img_perfiles/default.png';" alt="">
                                <?= htmlspecialchars($u['nombres'] . ' ' . $u['apellidos']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($u['correo']) ?></td>
                        <td><?= htmlspecialchars($u['distrito'] ?? '—') ?></td>
                        <td><?= date('d/m/Y', strtotime($u['fechaRegistro'])) ?></td>
                        <td>
                            <?php if ($u['rol'] === 'admin'): ?>
                                <span class="badge badge-naranja">Admin</span>
                            <?php else: ?>
                                <span class="badge badge-gris">Usuario</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $badgeUsuario[$u['estado']] ?? 'badge-gris' ?>">
                                <?= htmlspecialchars($u['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="acciones">
                                <?php if ((int) $u['idUsuario'] !== $idAdminActual): ?>
                                    <!-- Cambiar estado (los 4 valores reales) -->
                                    <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=cambiarEstadoUsuario" style="display:inline;">
                    <?= campoCsrf() ?>
                                        <input type="hidden" name="idUsuario" value="<?= (int) $u['idUsuario'] ?>">
                                        <select name="estado" onchange="this.form.submit()" class="btn-accion" style="cursor:pointer;">
                                            <?php foreach ($estadosUsuario as $e): ?>
                                                <option value="<?= $e ?>" <?= $u['estado'] === $e ? 'selected' : '' ?>><?= $e ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                    <!-- Promover / quitar admin -->
                                    <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=cambiarRol" style="display:inline;">
                    <?= campoCsrf() ?>
                                        <input type="hidden" name="idUsuario" value="<?= (int) $u['idUsuario'] ?>">
                                        <input type="hidden" name="rol" value="<?= $u['rol'] === 'admin' ? 'usuario' : 'admin' ?>">
                                        <button class="btn-accion" type="submit" title="<?= $u['rol'] === 'admin' ? 'Quitar admin' : 'Hacer admin' ?>">
                                            <?php if ($u['rol'] === 'admin'): ?>
                                                <i class="fa-solid fa-user-minus"></i> Quitar admin
                                            <?php else: ?>
                                                <i class="fa-solid fa-user-shield"></i> Hacer admin
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                    <!-- Eliminar (solo usuarios normales) -->
                                    <?php if ($u['rol'] !== 'admin'): ?>
                                        <button class="btn-accion peligro" data-abre-modal="modalEliminarUsuario"
                                                onclick="document.querySelector('#modalEliminarUsuario [name=idUsuario]').value=<?= (int) $u['idUsuario'] ?>; document.getElementById('nombreEliminarUsuario').textContent='<?= htmlspecialchars(addslashes($u['nombres'].' '.$u['apellidos'])) ?>'">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-azul">Tú (admin)</span>
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

<!-- Modal eliminar usuario -->
<div class="admin-modal-overlay" id="modalEliminarUsuario">
    <div class="admin-modal">
        <h3>Eliminar usuario</h3>
        <p>¿Seguro que deseas eliminar a <strong id="nombreEliminarUsuario"></strong>? Se borrarán sus anuncios, postulaciones y datos. Esta acción no se puede deshacer.</p>
        <form method="POST" action="<?= BASE_URL ?>controllers/AdminController.php?action=eliminarUsuario">
                    <?= campoCsrf() ?>
            <input type="hidden" name="idUsuario" value="">
            <div class="admin-modal-acciones">
                <button type="button" class="btn-modal cancelar" data-cierra-modal>Cancelar</button>
                <button type="submit" class="btn-modal confirmar peligro">Eliminar</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/_foot_admin.php'; ?>
