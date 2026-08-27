<?php
require_once __DIR__ . '/../models/ReporteModel.php';
require_once __DIR__ . '/../models/PostulacionModel.php';
require_once __DIR__ . '/../core/config/session.php';
// Necesario para verificarCsrf(): session.php ya no arrastra config.php,
// para no formar un ciclo entre ambos archivos.
require_once __DIR__ . '/../core/security/csrf.php';

class ReporteController {
    private ReporteModel $modelo;

    public function __construct() {
        $this->modelo = new ReporteModel();
    }

    public function reportar(): void {
        $idReporta = obtenerIdUsuarioActivo();
        $idAnuncio = (int) ($_POST['idAnuncio'] ?? 0);
        $tipo      = $_POST['tipo'] ?? 'trabajo';
        $motivo    = trim($_POST['motivo'] ?? '');
        $detalle   = trim($_POST['detalle'] ?? '');

        $motivosValidos = ['Spam', 'Contenido ofensivo', 'Estafa o fraude', 'Informacion falsa', 'Otro'];

        if ($idAnuncio <= 0 || !in_array($motivo, $motivosValidos)) {
            $this->redirigir($idAnuncio, $tipo, 'rep_error');
        }
        if ($this->modelo->yaReporto($idReporta, $idAnuncio)) {
            $this->redirigir($idAnuncio, $tipo, 'rep_duplicado');
        }

        // Buscamos al dueño del anuncio para dejarlo registrado en el reporte.
        $idReportado = (new PostulacionModel())->obtenerIdDuenioAnuncio($idAnuncio);

        $ok = $this->modelo->crear($idReporta, $idAnuncio, $idReportado, $motivo, $detalle);
        $this->redirigir($idAnuncio, $tipo, $ok ? 'reportado' : 'rep_error');
    }

    private function redirigir(int $idAnuncio, string $tipo, string $estado): never {
        header("Location: ../index.php?action=detalle-anuncio&id=" . $idAnuncio . "&tipo=" . urlencode($tipo) . "&estado=" . urlencode($estado));
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
    (new ReporteController())->reportar();
}
?>
