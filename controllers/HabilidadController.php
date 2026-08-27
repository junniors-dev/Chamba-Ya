<?php
require_once __DIR__ . '/../models/HabilidadModel.php';
require_once __DIR__ . '/../core/config/session.php';
require_once __DIR__ . '/../core/config/config.php';

class HabilidadController {
    private HabilidadModel $modelo;

    public function __construct() {
        $this->modelo = new HabilidadModel();
    }

    public function guardar(): void {
        $idUsuario = obtenerIdUsuarioActivo();
        $ids = $_POST['habilidades'] ?? [];
        if (!is_array($ids)) $ids = [];

        $this->modelo->guardarDeUsuario($idUsuario, $ids);
        header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=habilidades_ok');
        exit();
    }
}

// Pide estar logueado y método POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    iniciarSesion();
    // Corta la peticion si no trae un token CSRF valido: impide que otra
    // web dispare esta accion aprovechando la sesion abierta del usuario.
    verificarCsrf();
    if (!isset($_SESSION['idUsuario'])) {
        header('Location: ../views/auth/login.php');
        exit();
    }
    (new HabilidadController())->guardar();
}
?>
