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
            // Restaura el rol para que un admin con "recordarme" no pierda el panel.
            $_SESSION['rol']          = $usuario['rol'] ?? 'usuario';
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

    // ¿El usuario logueado es administrador?
    function esAdmin(): bool {
        iniciarSesion();
        return isset($_SESSION['idUsuario']) && ($_SESSION['rol'] ?? 'usuario') === 'admin';
    }

    // Guard para las páginas/acciones del panel: corta si no es admin.
    function requireAdmin(): void {
        iniciarSesion();
        if (!isset($_SESSION['idUsuario'])) {
            // No logueado -> al login
            header('Location: ' . BASE_URL . 'views/auth/login.php');
            exit();
        }
        if (($_SESSION['rol'] ?? 'usuario') !== 'admin') {
            // Logueado pero no admin -> al inicio
            header('Location: ' . BASE_URL . 'index.php');
            exit();
        }
    }
?>
