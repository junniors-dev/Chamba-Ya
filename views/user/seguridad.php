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

    // Mensajes de estado de contraseña
    $pass_msgs = [
        'success'  => ['type' => 'success', 'msg' => 'Contraseña actualizada correctamente.'],
        'wrong'    => ['type' => 'error',   'msg' => 'La contraseña actual es incorrecta.'],
        'mismatch' => ['type' => 'error',   'msg' => 'Las contraseñas nuevas no coinciden.'],
        'short'    => ['type' => 'error',   'msg' => 'La nueva contraseña debe tener al menos 8 caracteres.'],
        'empty'    => ['type' => 'error',   'msg' => 'Todos los campos son obligatorios.'],
        'error'    => ['type' => 'error',   'msg' => 'Ocurrió un error. Intenta de nuevo.'],
    ];
    $pass_status = $_GET['pass_status'] ?? null;
?>

<div class="profile-page">
    <section class="profile-hero">
        <div class="breadcrumb">
            <a href="<?= BASE_URL?>index.php">Inicio</a> &nbsp;/&nbsp; <a href="<?= BASE_URL?>controllers/AuthController.php?action=showMisDatos">Mi Perfil</a> &nbsp;/&nbsp; Seguridad
        </div>
        <h1>Seguridad</h1>
        <p>Protege tu cuenta y gestiona tus credenciales de acceso.</p>
    </section>

    <div class="profile-layout">
        <!-- Sidebar -->
        <aside class="profile-sidebar">
            <div class="profile-sidebar-card">
                <div class="sidebar-section">
                    <h4>Ajustes</h4>
                    <ul class="sidebar-nav">
                        <li><a href="<?= BASE_URL?>controllers/AuthController.php?action=showMisDatos"><i class="fa-solid fa-user"></i> Mis Datos</a></li>
                        <li><a href="#"><i class="fa-regular fa-bookmark"></i> Anuncios guardados</a></li>
                        <li><a href="#"><i class="fa-regular fa-square-plus"></i> Anuncios creados</a></li>
                    </ul>
                </div>
                <div class="sidebar-section">
                    <h4>Seguridad</h4>
                    <ul class="sidebar-nav">
                        <li><a href="<?= BASE_URL?>controllers/AuthController.php?action=showSeguridad" class="active"><i class="fa-solid fa-shield-halved"></i> Seguridad</a></li>
                        <li><a href="<?= BASE_URL?>controllers/AuthController.php?action=showPreferencias"><i class="fa-solid fa-sliders"></i> Preferencias</a></li>
                    </ul>
                </div>
            </div>
        </aside>

        <main class="profile-content">

            <!-- Alerta de estado -->
            <?php if($pass_status && isset($pass_msgs[$pass_status])): ?>
                <div class="alert alert-<?= $pass_msgs[$pass_status]['type'] ?>">
                    <i class="fa-solid fa-<?= $pass_msgs[$pass_status]['type'] === 'success' ? 'circle-check' : 'circle-exclamation' ?>"></i>
                    <?= $pass_msgs[$pass_status]['msg'] ?>
                </div>
            <?php endif; ?>

            <!-- Card: Cambio de Contraseña -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #dcfce7, #f0fdf4); color: #16a34a;"><i class="fa-solid fa-lock"></i></div>
                    <div>
                        <h2>Cambiar Contraseña</h2>
                        <span>Actualiza tu contraseña regularmente para mantener tu cuenta segura.</span>
                    </div>
                </div>

                <form action="<?= BASE_URL?>controllers/AuthController.php?action=changePassword" method="POST">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="currentPassword">Contraseña Actual</label>
                            <div class="input-password-wrapper">
                                <input type="password" id="currentPassword" name="currentPassword" placeholder="Ingresa tu contraseña actual">
                                <button type="button" class="toggle-password" onclick="togglePwd('currentPassword', this)"><i class="fa-regular fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">Nueva Contraseña</label>
                            <div class="input-password-wrapper">
                                <input type="password" id="newPassword" name="newPassword" placeholder="Mínimo 8 caracteres" oninput="checkStrength(this.value)">
                                <button type="button" class="toggle-password" onclick="togglePwd('newPassword', this)"><i class="fa-regular fa-eye"></i></button>
                            </div>
                            <!-- Barra de fortaleza -->
                            <div class="strength-bar" id="strengthBar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span class="strength-label" id="strengthLabel"></span>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirmar Nueva Contraseña</label>
                            <div class="input-password-wrapper">
                                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Repite tu nueva contraseña">
                                <button type="button" class="toggle-password" onclick="togglePwd('confirmPassword', this)"><i class="fa-regular fa-eye"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 24px;">
                        <button type="submit" class="btn-save">
                            <i class="fa-solid fa-key"></i> Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card: Zona de Peligro -->
            <div class="profile-card card-danger">
                <div class="profile-card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #fee2e2, #fef2f2); color: #dc2626;"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div>
                        <h2 style="color: #dc2626;">Zona de Peligro</h2>
                        <span>Opciones destructivas para tu cuenta. Proceder con precaución.</span>
                    </div>
                </div>

                <div class="danger-action-row">
                    <div>
                        <h3 class="danger-title">Desactivar Cuenta</h3>
                        <p class="danger-text">Tu perfil dejará de ser visible para los empleadores, pero podrás reactivarlo iniciando sesión nuevamente.</p>
                    </div>
                    <button type="button" class="btn-danger-outline" onclick="confirmarAccion('desactivar')">Desactivar cuenta</button>
                </div>

                <hr class="danger-divider">

                <div class="danger-action-row">
                    <div>
                        <h3 class="danger-title">Eliminar Cuenta Definitivamente</h3>
                        <p class="danger-text">Se borrarán todos tus datos, anuncios y postulaciones. Esta acción <strong>no se puede deshacer</strong>.</p>
                    </div>
                    <button type="button" class="btn-danger" onclick="confirmarAccion('eliminar')">Eliminar cuenta</button>
                </div>
            </div>

            <!-- Modal de confirmación -->
            <div id="dangerModal" class="modal-overlay" style="display:none;">
                <div class="modal-box">
                    <div class="modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <h3 id="modalTitle">¿Estás seguro?</h3>
                    <p id="modalText">Esta acción no se puede deshacer.</p>
                    <div class="modal-actions">
                        <button class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
                        <button id="modalConfirmBtn" class="btn-danger">Confirmar</button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
    // ===== MOSTRAR/OCULTAR CONTRASEÑA =====
    function togglePwd(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // ===== FORTALEZA DE CONTRASEÑA =====
    function checkStrength(val) {
        const fill = document.getElementById('strengthFill');
        const label = document.getElementById('strengthLabel');
        let score = 0;
        if(val.length >= 8) score++;
        if(/[A-Z]/.test(val)) score++;
        if(/[0-9]/.test(val)) score++;
        if(/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { pct: '0%',   color: '#e2e8f0', text: '' },
            { pct: '25%',  color: '#ef4444', text: 'Muy débil' },
            { pct: '50%',  color: '#f59e0b', text: 'Débil' },
            { pct: '75%',  color: '#3b82f6', text: 'Buena' },
            { pct: '100%', color: '#22c55e', text: 'Muy segura ✓' },
        ];
        fill.style.width = levels[score].pct;
        fill.style.background = levels[score].color;
        label.textContent = levels[score].text;
        label.style.color = levels[score].color;
    }

    // ===== MODAL ZONA DE PELIGRO =====
    function confirmarAccion(tipo) {
        const modal = document.getElementById('dangerModal');
        const title = document.getElementById('modalTitle');
        const text = document.getElementById('modalText');
        if(tipo === 'desactivar') {
            title.textContent = '¿Desactivar tu cuenta?';
            text.textContent = 'Tu perfil dejará de aparecer en búsquedas. Puedes reactivarla iniciando sesión en cualquier momento.';
        } else {
            title.textContent = '¿Eliminar tu cuenta definitivamente?';
            text.textContent = 'Se borrarán todos tus datos, anuncios y postulaciones. Esta acción NO se puede deshacer.';
        }
        modal.style.display = 'flex';
    }
    function cerrarModal() {
        document.getElementById('dangerModal').style.display = 'none';
    }
    // Cerrar al hacer clic fuera del modal
    document.getElementById('dangerModal').addEventListener('click', function(e) {
        if(e.target === this) cerrarModal();
    });
</script>

</body>
</html>
