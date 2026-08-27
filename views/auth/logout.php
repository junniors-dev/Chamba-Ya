<?php
    require_once __DIR__ . '/../../core/config/config.php';
    require_once __DIR__ . '/../../core/config/session.php';
    iniciarSesion();

    // Invalida el token "recordarme" en la BD: si no, la cookie seguiría
    // sirviendo para volver a entrar automáticamente después de cerrar sesión.
    if (isset($_SESSION['idUsuario'])) {
        require_once __DIR__ . '/../../core/db/database.php';
        require_once __DIR__ . '/../../models/userModel.php';
        (new UserModel())->limpiarRememberToken($_SESSION['idUsuario']);
    }

    // Borra la cookie con los mismos flags con que se creó (httponly, samesite):
    // si no coinciden, el navegador puede no llegar a eliminarla.
    borrarCookieRecordarme();

    // Destruye la sesión por completo, incluida su cookie.
    cerrarSesion();

    header('Location: ' . BASE_URL . 'index.php');
    exit();
