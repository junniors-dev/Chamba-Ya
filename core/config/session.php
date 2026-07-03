<?php
    function iniciarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Si no hay sesión pero sí una cookie "recordarme" válida, loguea automático.
        intentarAutoLoginPorCookie();
    }

    function intentarAutoLoginPorCookie(): void {
        // Ya hay sesión, o no hay cookie: no hacemos nada.
        if (isset($_SESSION['idUsuario']) || empty($_COOKIE['remember_token'])) {
            return;
        }

        require_once __DIR__ . '/../db/database.php';
        require_once __DIR__ . '/../../models/userModel.php';

        $tokenHash = hash('sha256', $_COOKIE['remember_token']);
        $usuario = (new UserModel())->getUserByRememberToken($tokenHash);

        if ($usuario) {
            $_SESSION['idUsuario']    = $usuario['idUsuario'];
            $_SESSION['nombres']      = $usuario['nombres'];
            $_SESSION['emailUsuario'] = $usuario['correo'];
        } else {
            // Token vencido o inválido: borramos la cookie basura.
            setcookie('remember_token', '', time() - 3600, '/');
        }
    }

    // 0 = nadie logueado.
    function obtenerIdUsuarioActivo(): int {
        iniciarSesion();
        if (!isset($_SESSION['idUsuario'])) {
            return 0;
        }
        return (int) $_SESSION['idUsuario'];
    }
?>
