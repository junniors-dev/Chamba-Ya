<?php
    // Formulario de reporte (colapsable). Requiere $anuncio y opcional $tipoReporte.
    $esDueno = isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] == $anuncio['idUsuario'];
    if ($esDueno) return; // No tiene sentido reportar el propio anuncio.
?>
<?php if (isset($_SESSION['idUsuario'])): ?>
    <details style="margin-top:15px;">
        <summary style="cursor:pointer;color:#dc2626;font-size:.9rem;"><i class="fa-solid fa-flag"></i> Reportar este anuncio</summary>
        <form action="<?= BASE_URL ?>controllers/ReporteController.php" method="POST" style="margin-top:10px;display:flex;flex-direction:column;gap:8px;max-width:420px;">
                    <?= campoCsrf() ?>
            <input type="hidden" name="idAnuncio" value="<?= (int) $anuncio['idAnuncio'] ?>">
            <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipoReporte ?? 'trabajo') ?>">
            <select name="motivo" required style="padding:8px;border:1px solid #cbd5e1;border-radius:6px;">
                <option value="">Motivo...</option>
                <option value="Spam">Spam</option>
                <option value="Contenido ofensivo">Contenido ofensivo</option>
                <option value="Estafa o fraude">Estafa o fraude</option>
                <option value="Informacion falsa">Información falsa</option>
                <option value="Otro">Otro</option>
            </select>
            <textarea name="detalle" maxlength="300" placeholder="Detalle (opcional)" style="padding:8px;border:1px solid #cbd5e1;border-radius:6px; resize:none"></textarea>
            <button type="submit" style="padding:9px;background:#dc2626;color:#fff;border:none;border-radius:6px;cursor:pointer;">Enviar reporte</button>
        </form>
    </details>
<?php else: ?>
    <p style="margin-top:15px;font-size:.85rem;"><a href="<?= BASE_URL ?>views/auth/login.php">Inicia sesión</a> para reportar.</p>
<?php endif; ?>
