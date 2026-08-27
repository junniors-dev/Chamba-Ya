<?php
require_once __DIR__ . '/../models/CalificacionModel.php';
require_once __DIR__ . '/../core/config/session.php';

class CalificacionController {
    private CalificacionModel $modelo;

    public function __construct() {
        $this->modelo = new CalificacionModel();
    }

    public function calificar(): void {
        $idCalificador = obtenerIdUsuarioActivo();
        $idCalificado  = (int) ($_POST['idUsuarioCalificado'] ?? 0);
        $idAnuncio     = (int) ($_POST['idAnuncio'] ?? 0);
        $puntaje       = (int) ($_POST['puntaje'] ?? 0);
        $comentario    = trim($_POST['comentario'] ?? '');

        if ($puntaje < 1 || $puntaje > 5) {
            $this->redirigir($idAnuncio, 'cal_invalida');
        }
        if ($idCalificado <= 0 || !$this->modelo->usuarioExiste($idCalificado)) {
            $this->redirigir($idAnuncio, 'error');
        }
        if ($idCalificado === $idCalificador) {
            $this->redirigir($idAnuncio, 'cal_propia');
        }

        if ($this->modelo->yaCalifico($idCalificador, $idCalificado)) {
            $ok = $this->modelo->actualizar($idCalificador, $idCalificado, $puntaje, $comentario);
        } else {
            $ok = $this->modelo->crear($idCalificador, $idCalificado, $puntaje, $comentario);
        }

        if ($ok) {
            require_once __DIR__ . '/../models/NotificacionModel.php';
            (new NotificacionModel())->notificar(
                $idCalificado,
                "Recibiste una nueva calificación de $puntaje estrella(s).",
                'controllers/AuthController.php?action=showMisDatos',
                'Nueva calificación en Chamba Ya'
            );
        }
        $this->redirigir($idAnuncio, $ok ? 'calificado' : 'error');
    }

    private function redirigir(int $idAnuncio, string $estado): never {
        header("Location: ../index.php?action=detalle-anuncio&id=" . $idAnuncio . "&tipo=servicio&estado=" . urlencode($estado));
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    iniciarSesion();
    // Corta la peticion si no trae un token CSRF valido: impide que otra
    // web dispare esta accion aprovechando la sesion abierta del usuario.
    verificarCsrf();
    if (!isset($_SESSION['idUsuario'])) {
        header('Location: ../views/auth/login.php');
        exit();
    }
    (new CalificacionController())->calificar();
}
?>
