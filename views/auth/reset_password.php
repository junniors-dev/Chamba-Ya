<?php
    /**
     * Paso 2 del restablecimiento: el usuario llegó con un token válido.
     * La vista solo se muestra si AuthController::showResetForm ya comprobó
     * el token contra la base de datos; aquí no se vuelve a confiar en él,
     * se reenvía y el servidor lo valida otra vez al guardar.
     */
    require_once __DIR__ . '/../../core/config/config.php';
    require_once __DIR__ . '/../../assets/css/style.php';
    require_once __DIR__ . '/../../assets/css/styles.php';
    require_once __DIR__ . '/../../assets/css/style_lr.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Nueva contraseña - Chamba Ya</title>
</head>

<body>
    <div class="container" style="min-height:100vh;display:flex;align-items:center;justify-content:center;">
        <div class="form_box" style="position:relative;width:100%;max-width:420px;padding:30px;">
            <form action="<?= BASE_URL ?>controllers/AuthController.php?action=resetPassword" method="POST">
                <?= campoCsrf() ?>
                <!-- El token viaja en el POST y se vuelve a validar en el servidor. -->
                <input type="hidden" name="token" value="<?= htmlspecialchars($tokenReset, ENT_QUOTES, 'UTF-8') ?>">

                <h1>Nueva contraseña</h1>

                <?php if(isset($_GET['rec_status'])): ?>
                    <?php
                        $msgs = [
                            'mismatch' => 'Las contraseñas no coinciden.',
                            'short'    => 'La contraseña debe tener al menos 8 caracteres.',
                            'error'    => 'No se pudo actualizar la contraseña.',
                        ];
                        $msg = $msgs[$_GET['rec_status']] ?? 'Ocurrió un error.';
                    ?>
                    <p class="form_msg form_msg_error" style="color:#dc2626;"><?= htmlspecialchars($msg) ?></p>
                <?php endif; ?>

                <p style="font-size:.9rem;color:#555;margin-bottom:10px;">
                    Escribe tu nueva contraseña. Al guardarla se cerrarán las sesiones
                    abiertas en otros dispositivos y este enlace dejará de funcionar.
                </p>

                <div class="input_box">
                    <input type="password" placeholder="Nueva contraseña" required
                           name="newPassword" minlength="8" maxlength="72" autocomplete="new-password">
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input_box">
                    <input type="password" placeholder="Confirmar nueva contraseña" required
                           name="confirmPassword" minlength="8" maxlength="72" autocomplete="new-password">
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <button type="submit" class="btn_link">Guardar contraseña</button>
                <p style="margin-top:14px;text-align:center;">
                    <a href="<?= BASE_URL ?>views/auth/login.php">Volver a iniciar sesión</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
