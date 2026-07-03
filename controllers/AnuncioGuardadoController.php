<?php
require_once __DIR__ . '/../models/AnuncioGuardadoModel.php';
require_once __DIR__ . '/../core/config/session.php';
class AnuncioGuardadoController {
    private AnuncioGuardadoModel $modelo;

    public function __construct() {
        $this->modelo = new AnuncioGuardadoModel();
    }

    public function obtenerAnunciosFavoritos() {
        $idUsuario = obtenerIdUsuarioActivo();
        return $this->modelo->obtenerAnunciosFavoritos($idUsuario);
    }

    public function alternarFavorito(): void {
        $idUsuario = obtenerIdUsuarioActivo();
        $idAnuncio = (int) ($_POST['idAnuncio'] ?? 0);
        $esAjax = $this->esPeticionAjax();

        if ($idAnuncio <= 0 || !$this->modelo->anuncioExiste($idAnuncio)) {
            $this->responder($esAjax, $idAnuncio, false, 'error');
        }

        if ($this->modelo->esFavorito($idUsuario, $idAnuncio)) {
            $ok = $this->modelo->quitarFavorito($idUsuario, $idAnuncio);
            $this->responder($esAjax, $idAnuncio, false, $ok ? 'fav_quitado' : 'error');
        } else {
            $ok = $this->modelo->agregarFavorito($idUsuario, $idAnuncio);
            $this->responder($esAjax, $idAnuncio, true, $ok ? 'fav_guardado' : 'error');
        }
    }

    private function esPeticionAjax(): bool {
        return ($_POST['ajax'] ?? '') === '1'
            || (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest');
    }

    // Con AJAX responde JSON; si no, mantiene el comportamiento clásico (redirect).
    private function responder(bool $esAjax, int $idAnuncio, bool $esFavorito, string $estado): never {
        if ($esAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok' => $estado !== 'error',
                'esFavorito' => $esFavorito,
                'estado' => $estado,
            ]);
            exit();
        }
        $this->redirigir($idAnuncio, $estado);
    }

    private function redirigir(int $idAnuncio, string $estado): never {
        // Si vino desde la lista de guardados, vuelve a esa lista.
        if (($_POST['origen'] ?? '') === 'guardados') {
            header("Location: ../views/user/mis_guardados.php?estado=" . urlencode($estado));
        } else {
            header("Location: ../index.php?action=detalle-anuncio&id=" . $idAnuncio . "&estado=" . urlencode($estado));
        }
        exit();
    }
}

// Pide estar logueado.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    iniciarSesion();
    if (!isset($_SESSION['idUsuario'])) {
        header('Location: ../views/auth/login.php');
        exit();
    }
    (new AnuncioGuardadoController())->alternarFavorito();
}
?>
