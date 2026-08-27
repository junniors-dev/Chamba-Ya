<?php
require_once __DIR__ . '/../models/TrabajadorFavoritoModel.php';
require_once __DIR__ . '/../core/config/session.php';
// Necesario para verificarCsrf(): session.php ya no arrastra config.php,
// para no formar un ciclo entre ambos archivos.
require_once __DIR__ . '/../core/security/csrf.php';

class TrabajadorFavoritoController {
    private TrabajadorFavoritoModel $modelo;

    public function __construct() {
        $this->modelo = new TrabajadorFavoritoModel();
    }

    public function obtenerFavoritos() {
        return $this->modelo->obtenerFavoritos(obtenerIdUsuarioActivo());
    }

    public function alternar(): void {
        $idCliente    = obtenerIdUsuarioActivo();
        $idTrabajador = (int) ($_POST['idTrabajador'] ?? 0);
        $esAjax = $this->esPeticionAjax();

        if ($idTrabajador <= 0 || !$this->modelo->usuarioExiste($idTrabajador)) {
            $this->responder($esAjax, false, 'error');
        }
        if ($idTrabajador === $idCliente) {
            $this->responder($esAjax, false, 'trab_propio');
        }

        if ($this->modelo->esFavorito($idCliente, $idTrabajador)) {
            $ok = $this->modelo->quitar($idCliente, $idTrabajador);
            $this->responder($esAjax, false, $ok ? 'trab_quitado' : 'error');
        } else {
            $ok = $this->modelo->agregar($idCliente, $idTrabajador);
            $this->responder($esAjax, true, $ok ? 'trab_guardado' : 'error');
        }
    }

    private function esPeticionAjax(): bool {
        return ($_POST['ajax'] ?? '') === '1'
            || (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest');
    }

    private function responder(bool $esAjax, bool $esFavorito, string $estado): never {
        if ($esAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok' => $estado !== 'error' && $estado !== 'trab_propio',
                'esFavorito' => $esFavorito,
                'estado' => $estado,
            ]);
            exit();
        }
        $this->redirigir($estado);
    }

    private function redirigir(string $estado): never {
        if (($_POST['origen'] ?? '') === 'lista') {
            header("Location: ../views/user/trabajadores_favoritos.php?estado=" . urlencode($estado));
        } else {
            $idAnuncio = (int) ($_POST['idAnuncio'] ?? 0);
            header("Location: ../index.php?action=detalle-anuncio&id=" . $idAnuncio . "&tipo=servicio&estado=" . urlencode($estado));
        }
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
    (new TrabajadorFavoritoController())->alternar();
}
?>
