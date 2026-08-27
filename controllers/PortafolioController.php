<?php
/**
 * Portafolio de trabajos anteriores del usuario.
 * Solo se opera sobre el portafolio propio: el id de la sesión manda siempre,
 * nunca uno que venga del formulario.
 */
require_once __DIR__ . '/../core/config/autoload.php';
require_once __DIR__ . '/../core/config/config.php';
require_once __DIR__ . '/../core/config/session.php';
require_once __DIR__ . '/../models/PortafolioModel.php';

class PortafolioController {
    private PortafolioModel $modelo;

    // Mismas reglas que en la foto de perfil.
    const MAX_BYTES = 2 * 1024 * 1024; // 2 MB
    const EXTENSIONES = ['jpg', 'jpeg', 'png', 'webp'];
    const MIMES       = ['image/jpeg', 'image/png', 'image/webp'];

    public function __construct() {
        $this->modelo = new PortafolioModel();
    }

    public function obtenerDeUsuario($idUsuario): array {
        return $this->modelo->obtenerDeUsuario($idUsuario);
    }

    public function guardar(): void {
        $idUsuario = obtenerIdUsuarioActivo();

        $titulo      = limpiarTexto($_POST['titulo'] ?? '', 120);
        $descripcion = limpiarTexto($_POST['descripcion'] ?? '', 1000);
        $idCategoria = !empty($_POST['idCategoria']) ? (int) $_POST['idCategoria'] : null;

        if ($titulo === '') {
            $this->volver('port_titulo');
        }

        // Tope por usuario: sin él, cualquiera podría llenar el disco del
        // servidor subiendo imágenes sin fin.
        if ($this->modelo->contarDeUsuario($idUsuario) >= PortafolioModel::MAX_POR_USUARIO) {
            $this->volver('port_limite');
        }

        $nombreImagen = $this->procesarImagen();
        if ($nombreImagen === false) {
            $this->volver('port_imagen');
        }

        $ok = $this->modelo->crear($idUsuario, $titulo, $descripcion, $nombreImagen, $idCategoria);
        $this->volver($ok ? 'port_guardado' : 'port_error');
    }

    /**
     * Devuelve el nombre del archivo guardado, null si no se subió imagen,
     * o false si la imagen no pasa las validaciones.
     */
    private function procesarImagen() {
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            return null; // el trabajo puede no llevar foto
        }

        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::EXTENSIONES, true)) {
            return false;
        }
        if ($_FILES['imagen']['size'] > self::MAX_BYTES) {
            return false;
        }

        // Se comprueba el tipo REAL del archivo: renombrar un .php a .jpg no
        // cambia su contenido, y la extensión por sí sola no prueba nada.
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeReal = finfo_file($finfo, $_FILES['imagen']['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mimeReal, self::MIMES, true)) {
            return false;
        }

        // El nombre lo genera el servidor: el del cliente podría traer "../".
        $nombre = 'port_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $destino = __DIR__ . '/../assets/uploads/portafolio/' . $nombre;

        if (!is_dir(dirname($destino))) {
            mkdir(dirname($destino), 0755, true);
        }
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            return false;
        }
        return $nombre;
    }

    public function eliminar(): void {
        $idUsuario    = obtenerIdUsuarioActivo();
        $idPortafolio = (int) ($_POST['idPortafolio'] ?? 0);

        if ($idPortafolio <= 0 || !$this->modelo->perteneceAUsuario($idPortafolio, $idUsuario)) {
            $this->volver('port_no_autorizado');
        }

        // Se lee el nombre ANTES de borrar la fila, para poder limpiar el disco.
        $imagen = $this->modelo->obtenerImagen($idPortafolio);
        $ok = $this->modelo->eliminar($idPortafolio, $idUsuario);

        if ($ok && $imagen) {
            $ruta = __DIR__ . '/../assets/uploads/portafolio/' . basename($imagen);
            if (is_file($ruta)) { @unlink($ruta); }
        }
        $this->volver($ok ? 'port_eliminado' : 'port_error');
    }

    private function volver(string $estado): never {
        header('Location: ' . BASE_URL . 'controllers/AuthController.php?action=showMisDatos&status=' . urlencode($estado));
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    iniciarSesion();
    if (!isset($_SESSION['idUsuario'])) {
        header('Location: ' . BASE_URL . 'views/auth/login.php');
        exit();
    }
    // Corta la petición si no trae un token CSRF válido.
    verificarCsrf();

    $ctrl = new PortafolioController();
    if (($_POST['accion'] ?? '') === 'eliminar') {
        $ctrl->eliminar();
    } else {
        $ctrl->guardar();
    }
}
