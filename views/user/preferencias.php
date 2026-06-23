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
        <aside class="profile-sidebar">
            <div class="profile-sidebar-card">
                <div class="sidebar-section">
                    <h4>Ajustes</h4>
                    <ul class="sidebar-nav">
                        <li><a href="<?= BASE_URL ?>controllers/AuthController.php?action=showMisDatos"><i class="fa-solid fa-user"></i> Mis Datos</a></li>
                        <li><a href="#"><i class="fa-regular fa-bookmark"></i> Anuncios guardados</a></li>
                        <li><a href="#"><i class="fa-regular fa-square-plus"></i> Anuncios creados</a></li>
                    </ul>
                </div>
                <div class="sidebar-section">
                    <h4>Seguridad</h4>
                    <ul class="sidebar-nav">
                        <li><a href="<?= BASE_URL ?>controllers/AuthController.php?action=showSeguridad"><i class="fa-solid fa-shield-halved"></i> Seguridad</a></li>
                        <li><a href="<?= BASE_URL ?>controllers/AuthController.php?action=showPreferencias" class="active"><i class="fa-solid fa-sliders"></i> Preferencias</a></li>
                    </ul>
                </div>
            </div>
        </aside>

        <main class="profile-content">

            <!-- Alerta de guardado (se muestra con JS) -->
            <div class="alert alert-success" id="prefSavedAlert" style="display:none;">
                <i class="fa-solid fa-circle-check"></i> Preferencias guardadas correctamente.
            </div>

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
                        <input type="checkbox" id="notif_ofertas">
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
                        <input type="checkbox" id="notif_vistas">
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
                        <input type="checkbox" id="notif_boletin">
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
                    <select id="visibilidad" class="profile-select" style="margin-top: 8px;">
                        <option value="publico">Público (Recomendado) — Visible para todos los empleadores</option>
                        <option value="solo_empresas">Solo Empresas Verificadas</option>
                        <option value="oculto">Oculto — Solo podré postular a ofertas manualmente</option>
                    </select>
                    <span class="input-hint" style="margin-top: 8px;"><i class="fa-solid fa-circle-info"></i> Ocultar tu perfil no eliminará tus postulaciones activas.</span>
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <button type="button" class="btn-cancel" onclick="resetearPreferencias()">
                        <i class="fa-solid fa-rotate-left"></i> Restablecer
                    </button>
                    <button type="button" class="btn-save" onclick="guardarPreferencias()">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Preferencias
                    </button>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
    const PREF_KEY = 'chambaYaPrefs_<?= $_SESSION['idUsuario'] ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/functions_preferencias.js"></script>

</body>
</html>
