<?php
require_once __DIR__ . '/../models/PostulacionModel.php';
require_once __DIR__ . '/../core/config/session.php';
class PostulacionController {
    private PostulacionModel $modelo;

    public function __construct() {
        $this->modelo = new PostulacionModel();
    }

    public function obtenerPostulaciones() {
        $idUsuario = obtenerIdUsuarioActivo();
        return $this->modelo->obtenerPostulaciones($idUsuario);
    }

    public function postular(): void {
        $idUsuario = obtenerIdUsuarioActivo();
        $idAnuncio = (int) ($_POST['idAnuncio'] ?? 0);

        if ($idAnuncio <= 0) {
            $this->redirigir($idAnuncio, 'error');
        }

        $dueno = $this->modelo->obtenerIdDuenioAnuncio($idAnuncio);
        if ($dueno === null) {
            $this->redirigir($idAnuncio, 'error');
        }
        if ($dueno === $idUsuario) {
            $this->redirigir($idAnuncio, 'propio');
        }
        // Solo se puede postular a anuncios disponibles (no cancelados/finalizados/en proceso)
        if ($this->modelo->obtenerEstadoAnuncio($idAnuncio) !== 'Disponible') {
            $this->redirigir($idAnuncio, 'no_disponible');
        }
        if ($this->modelo->yaPostulado($idUsuario, $idAnuncio)) {
            $this->redirigir($idAnuncio, 'duplicado');
        }

        $ok = $this->modelo->crearPostulacion($idUsuario, $idAnuncio);
        if ($ok) {
            require_once __DIR__ . '/../models/NotificacionModel.php';
            (new NotificacionModel())->notificar(
                $dueno,
                'Tienes una nueva postulación en uno de tus anuncios.',
                'views/user/postulaciones_recibidas.php',
                'Nueva postulación en Chamba Ya'
            );
        }
        $this->redirigir($idAnuncio, $ok ? 'postulado' : 'error');
    }

    private function redirigir(int $idAnuncio, string $estado): never {
        header("Location: ../index.php?action=detalle-anuncio&id=" . $idAnuncio . "&estado=" . urlencode($estado));
        exit();
    }

    public function obtenerRecibidas() {
        $idDueno = obtenerIdUsuarioActivo();
        return $this->modelo->obtenerPostulacionesRecibidas($idDueno);
    }

    public function gestionar(): void {
        $idDueno       = obtenerIdUsuarioActivo();
        $idPostulacion = (int) ($_POST['idPostulacion'] ?? 0);
        $decision      = $_POST['decision'] ?? '';

        $mapa = ['aceptar' => 'Aceptado', 'rechazar' => 'Rechazado'];
        if (!isset($mapa[$decision]) || $idPostulacion <= 0) {
            $this->redirigirRecibidas('error');
        }

        $ok = $this->modelo->actualizarEstado($idPostulacion, $mapa[$decision], $idDueno);
        if ($ok) {
            $idPostulante = $this->modelo->obtenerUsuarioDePostulacion($idPostulacion);
            if ($idPostulante) {
                require_once __DIR__ . '/../models/NotificacionModel.php';
                $estadoTxt = $decision === 'aceptar' ? 'aceptada' : 'rechazada';
                (new NotificacionModel())->notificar(
                    $idPostulante,
                    "Tu postulación fue $estadoTxt.",
                    'views/user/mis_postulaciones.php',
                    'Actualización de tu postulación'
                );
            }
        }
        $this->redirigirRecibidas($ok ? ($decision === 'aceptar' ? 'aceptada' : 'rechazada') : 'error');
    }

    private function redirigirRecibidas(string $estado): never {
        header("Location: ../views/user/postulaciones_recibidas.php?estado=" . urlencode($estado));
        exit();
    }
}

// Pide estar logueado.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    iniciarSesion();
    // Corta la peticion si no trae un token CSRF valido: impide que otra
    // web dispare esta accion aprovechando la sesion abierta del usuario.
    verificarCsrf();
    if (!isset($_SESSION['idUsuario'])) {
        header('Location: ../views/auth/login.php');
        exit();
    }
    $ctrl = new PostulacionController();
    if (($_POST['accion'] ?? '') === 'gestionar') {
        $ctrl->gestionar();
    } else {
        $ctrl->postular();
    }
}
?>
