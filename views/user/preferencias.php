<?php
    if(!isset($_SESSION['idUsuario'])){
        header('Location: ../auth/login.php');
        exit();
    }
    require_once __DIR__ . '/../../assets/css/style.php';
    require_once __DIR__ . '/../../assets/css/styles.php';
    require_once __DIR__ . '/../../assets/css/style_profile.php';
    require_once __DIR__ . '/../templates/head.php';
    require_once __DIR__ . '/../templates/header.php';
?>

<div class="profile-page">
    <section class="profile-hero">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>index.php">Inicio</a> &nbsp;/&nbsp; <a href="<?= BASE_URL ?>controllers/AuthController.php?action=showMisDatos">Mi Perfil</a> &nbsp;/&nbsp; Preferencias
        </div>
        <h1>Preferencias</h1>
        <p>Personaliza tu experiencia, privacidad y notificaciones.</p>
    </section>

    <div class="profile-layout">
        <!-- Sidebar -->
        <?php $paginaActual = 'preferencias'; require_once __DIR__ . '/../templates/profile_sidebar.php'; ?>

        <main class="profile-content">

            <?php $preferencias = $preferencias ?? ['notif_ofertas'=>1, 'notif_vistas'=>1, 'notif_boletin'=>0, 'visibilidad'=>'publico']; ?>

            <?php if(isset($_GET['pref_status']) && $_GET['pref_status'] === 'success'): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> Preferencias guardadas correctamente.
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>controllers/AuthController.php?action=guardarPreferencias">
                    <?= campoCsrf() ?>

            <!-- Card: Notificaciones -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #fef08a, #fef9c3); color: #ca8a04;"><i class="fa-regular fa-bell"></i></div>
                    <div>
                        <h2>Notificaciones por Correo</h2>
                        <span>Controla qué correos electrónicos te enviamos.</span>
                    </div>
                </div>

                <div class="toggle-row">
                    <div class="toggle-info">
                        <h3>Nuevas Ofertas de Trabajo</h3>
                        <p>Avisarme cuando se publiquen ofertas en mi área de interés.</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="notif_ofertas" name="notif_ofertas" <?= !empty($preferencias['notif_ofertas']) ? 'checked' : '' ?>>
                        <span class="slider round"></span>
                    </label>
                </div>

                <hr class="danger-divider">

                <div class="toggle-row">
                    <div class="toggle-info">
                        <h3>Vistas de Perfil</h3>
                        <p>Recibir un resumen semanal de quién ha visto mi perfil.</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="notif_vistas" name="notif_vistas" <?= !empty($preferencias['notif_vistas']) ? 'checked' : '' ?>>
                        <span class="slider round"></span>
                    </label>
                </div>

                <hr class="danger-divider">

                <div class="toggle-row">
                    <div class="toggle-info">
                        <h3>Boletín de Chamba Ya</h3>
                        <p>Noticias, consejos de búsqueda de empleo y actualizaciones de la plataforma.</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="notif_boletin" name="notif_boletin" <?= !empty($preferencias['notif_boletin']) ? 'checked' : '' ?>>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <!-- Card: Visibilidad del Perfil -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #e0e7ff, #eef2ff); color: #4f46e5;"><i class="fa-regular fa-eye"></i></div>
                    <div>
                        <h2>Visibilidad del Perfil</h2>
                        <span>Elige quién puede ver tu perfil en las búsquedas.</span>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="visibilidad">Nivel de Visibilidad</label>
                    <select id="visibilidad" name="visibilidad" class="profile-select" style="margin-top: 8px;">
                        <option value="publico" <?= $preferencias['visibilidad']==='publico' ? 'selected' : '' ?>>Público (Recomendado) — Visible para todos los empleadores</option>
                        <option value="solo_empresas" <?= $preferencias['visibilidad']==='solo_empresas' ? 'selected' : '' ?>>Solo Empresas Verificadas</option>
                        <option value="oculto" <?= $preferencias['visibilidad']==='oculto' ? 'selected' : '' ?>>Oculto — Solo podré postular a ofertas manualmente</option>
                    </select>
                    <span class="input-hint" style="margin-top: 8px;"><i class="fa-solid fa-circle-info"></i> Ocultar tu perfil no eliminará tus postulaciones activas.</span>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <button type="button" class="btn-cancel" onclick="restablecerDefaults()">
                        <i class="fa-solid fa-rotate-left"></i> Restablecer
                    </button>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Preferencias
                    </button>
                </div>
            </div>
            </form>

        </main>
    </div>
</div>

<script>
    // Restablece los campos a sus valores por defecto (no guarda hasta dar "Guardar").
    function restablecerDefaults() {
        document.getElementById('notif_ofertas').checked = true;
        document.getElementById('notif_vistas').checked = true;
        document.getElementById('notif_boletin').checked = false;
        document.getElementById('visibilidad').value = 'publico';
    }
</script>

</body>
</html>
