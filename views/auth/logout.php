<?php
    require_once __DIR__ . '/../../core/config/config.php';
    require_once __DIR__ . '/../../core/config/session.php';
    iniciarSesion();

    // Invalida el token "recordarme" en la BD y borra la cookie.
    if (isset($_SESSION['idUsuario'])) {
        require_once __DIR__ . '/../../core/db/database.php';
        require_once __DIR__ . '/../../models/userModel.php';
        (new UserModel())->limpiarRememberToken($_SESSION['idUsuario']);
    }
    if (!empty($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }

    //Eliminar todas las variables de sesión
    $_SESSION = [];

    //Destruir la sesión
    session_destroy();

    //Redirigir a la pagina de inicio
    header('Location: ' . BASE_URL . 'index.php');
    exit();
?>