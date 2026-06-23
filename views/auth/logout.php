<?php
    session_start();

    require_once __DIR__ . '/../../core/config/config.php';

    //Eliminar todas las variables de sesión
    $_SESSION = [];

    //Destruir la sesión
    session_destroy();

    //Redirigir a la pagina de inicio
    header('Location: ' . BASE_URL . 'index.php');
    exit();
?>