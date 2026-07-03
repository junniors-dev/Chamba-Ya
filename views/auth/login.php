<?php 
    require_once __DIR__ . '/../../core/config/config.php';
    require_once '../../assets/css/style.php';
    require_once '../../assets/css/styles.php';
    require_once '../../assets/css/style_lr.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="../../assets/js/function_lr.js"></script>
    <title>Iniciar Sesión / Registrarse - Chamba Ya</title>
</head>

<body>
    <!-- Logo (dentro del fondo) clickeable: vuelve al inicio -->
    <a href="<?= BASE_URL ?>index.php" class="logo-home-link" aria-label="Volver al inicio" title="Volver al inicio"></a>

    <!-- Apartado de inicio de sesión -->
    <div class="container">
        <div class="form_box login">
            <form action="../../controllers/AuthController.php?action=login" method="post" id="loginForm">
                <h1>Iniciar Sesión</h1>
                <?php if(isset($_GET['login_status'])):
                    $infoMsgs = [
                        'pass_reset'         => 'Contraseña actualizada. Inicia sesión con tu nueva contraseña.',
                        'cuenta_desactivada' => 'Tu cuenta fue desactivada. Inicia sesión para reactivarla.',
                    ];
                    $errMsgs = [
                        'not_found'      => 'El usuario no está registrado.',
                        'wrong_password' => 'Contraseña incorrecta.',
                    ];
                    $ls = $_GET['login_status'];
                ?>
                    <?php if(isset($infoMsgs[$ls])): ?>
                        <p class="form_msg" style="color:#16a34a;"><?= htmlspecialchars($infoMsgs[$ls]) ?></p>
                    <?php else: ?>
                        <p class="form_msg form_msg_error"><?= htmlspecialchars($errMsgs[$ls] ?? 'Ocurrió un error al iniciar sesión.') ?></p>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="input_box">
                    <input type="email" placeholder="Email" required name="emailInput" >
                    <i class='bx bxs-user' ></i>
                </div>
                <div class="input_box">
                    <input type="password" placeholder="Contraseña" required name="passwordInput">
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                <div class="link_olvido">
                    <a href="<?= BASE_URL ?>controllers/AuthController.php?action=showRecuperar" class="btn_forgot_pass">¿Olvidó su contraseña?</a>
                </div>
                <button type="submit" class="btn_link" name="login">Iniciar Sesión</button>
                <p>o inicie sesión con</p>
                <div class="iconos_redes">
                    <a href="#"><i class='bx bxl-google' ></i></a>
                    <a href="#"><i class='bx bxl-facebook' ></i></a>
                    <a href="#"><i class='bx bxl-instagram' ></i></a>
                    <a href="#"><i class='bx bxl-linkedin-square' ></i></a>
                </div>
            </form>
        </div>

        <!-- Apartado de registro de datos -->

        <div class="form_box register">
            <form action="../../controllers/AuthController.php?action=registerFirst" method="POST" id="registerForm">
                <h1>Registrarse</h1>
                <?php if(isset($_GET['reg_status'])): ?>
                    <?php
                        $regMsgs = [
                            'email_exists' => 'El correo ya está registrado.',
                            'mismatch'     => 'Las contraseñas no coinciden.',
                            'bad_format'   => 'Imagen no válida. Solo JPG o PNG.',
                            'too_big'      => 'La imagen supera el tamaño máximo (2 MB).',
                            'error'        => 'No se pudo completar el registro.',
                        ];
                        $msg = $regMsgs[$_GET['reg_status']] ?? 'Ocurrió un error en el registro.';
                    ?>
                    <p class="form_msg form_msg_error"><?= htmlspecialchars($msg) ?></p>
                <?php endif; ?>
                <div class="input_box">
                    <input type="email" placeholder="Email" required name="emailInput">
                    <i class='bx bxs-envelope' ></i>
                </div>
                <div class="input_box">
                    <input type="password" placeholder="Contraseña" required name="password">
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                <div class="input_box">
                    <input type="password" placeholder="Confirme su contraseña" required name="confirmPassword">
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                <button type="submit" name="registro" class="btn_link">Registrarse</button>
                <p>o registrese con</p>
                <div class="iconos_redes">
                    <a href="#"><i class='bx bxl-google' ></i></a>
                    <a href="#"><i class='bx bxl-facebook' ></i></a>
                    <a href="#"><i class='bx bxl-instagram' ></i></a>
                    <a href="#"><i class='bx bxl-linkedin-square' ></i></a>
                </div>
            </form>
        </div>

        <div class="toggle_box">
            <div class="toggle_panel toggle_left">
                <h1>Bienvenido/a denuevo!</h1>
                <p>No tienes cuenta con nosotros?</p>
                <button class="btn register_btn">Registrarse</button>
            </div>
            <div class="toggle_panel toggle_right">
                <h1>Hola, Bienvenido/a!</h1>
                <p>Ya tienes cuenta con nosotros?</p>
                <button class="btn login_btn">Inicia Sesión</button>
            </div>
        </div>
    </div>
</body>

</html>
