<?php
    require_once __DIR__ . '/../../core/config/config.php';
    require_once __DIR__ . '/../../core/db/database.php';
    require_once __DIR__ . '/../../assets/css/style.php';
    require_once __DIR__ . '/../../assets/css/styles.php';
    require_once __DIR__ . '/../../assets/css/style_lr.php';

    // Enlace mostrado solo en modo desarrollo (XAMPP no envía correos).
    // Se lee una vez y se borra, para que no quede en la sesión.
    $enlaceDev = $_SESSION['reset_enlace_dev'] ?? null;
    unset($_SESSION['reset_enlace_dev']);
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Recuperar contraseña - Chamba Ya</title>
</head>

<body>
    <div class="container" style="min-height:100vh;display:flex;align-items:center;justify-content:center;">
        <div class="form_box" style="position:relative;width:100%;max-width:420px;padding:30px;">
            <form action="<?= BASE_URL ?>controllers/AuthController.php?action=solicitarReset" method="POST">
                <?= campoCsrf() ?>
                <h1>Recuperar contraseña</h1>

                <?php if(isset($_GET['rec_status'])): ?>
                    <?php
                        $recMsgs = [
                            // Mensaje deliberadamente idéntico exista o no la cuenta:
                            // decir "ese correo no está registrado" convertiría esta
                            // página en un buscador de cuentas válidas.
                            'enviado'        => ['ok',    'Si el correo corresponde a una cuenta, te enviamos un enlace para restablecer la contraseña. Revisa tu bandeja de entrada.'],
                            'email_invalido' => ['error', 'Escribe un correo válido.'],
                            'token_invalido' => ['error', 'El enlace no es válido o ya caducó. Solicita uno nuevo.'],
                            'error'          => ['error', 'No se pudo procesar la solicitud. Inténtalo de nuevo.'],
                        ];
                        [$tipo, $msg] = $recMsgs[$_GET['rec_status']] ?? ['error', 'Ocurrió un error.'];
                        $color = $tipo === 'ok' ? '#16a34a' : '#dc2626';
                    ?>
                    <p class="form_msg" style="color:<?= $color ?>;"><?= htmlspecialchars($msg) ?></p>
                <?php endif; ?>

                <?php if($enlaceDev): ?>
                    <!-- Solo aparece con modo_dev = true en core/config/env.php.
                         En producción el enlace únicamente viaja por correo. -->
                    <div style="background:#fef9c3;border:1px solid #facc15;border-radius:8px;padding:12px;margin-bottom:14px;font-size:.85rem;word-break:break-all;">
                        <strong>Modo desarrollo:</strong> XAMPP no envía correos, así que aquí tienes el enlace.<br>
                        <a href="<?= htmlspecialchars($enlaceDev) ?>"><?= htmlspecialchars($enlaceDev) ?></a>
                    </div>
                <?php endif; ?>

                <p style="font-size:.9rem;color:#555;margin-bottom:10px;">
                    Escribe el <strong>correo</strong> con el que te registraste y te enviaremos
                    un enlace para crear una contraseña nueva. El enlace caduca en 30 minutos.
                </p>

                <div class="input_box">
                    <input type="email" placeholder="Correo registrado" required name="emailInput" maxlength="80">
                    <i class='bx bxs-envelope'></i>
                </div>

                <button type="submit" class="btn_link">Enviar enlace</button>
                <p style="margin-top:14px;text-align:center;">
                    <a href="<?= BASE_URL ?>views/auth/login.php">Volver a iniciar sesión</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
