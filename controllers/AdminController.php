<?php
require_once __DIR__ . '/../core/config/config.php';
require_once __DIR__ . '/../core/config/session.php';
require_once __DIR__ . '/../models/AdminModel.php';

class AdminController {
    private AdminModel $modelo;
    private int $porPagina = 15;

    public function __construct() {
        $this->modelo = new AdminModel();
    }

    private function pagina(): int {
        return max(1, (int) ($_GET['pagina'] ?? 1));
    }

    /* ===================== DASHBOARD ===================== */
    public function dashboard(): void {
        requireAdmin();
        $stats = $this->modelo->obtenerEstadisticas();
        $seccionActiva = 'dashboard';
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    /* ===================== USUARIOS ===================== */
    public function usuarios(): void {
        requireAdmin();
        $filtros = [
            'buscar' => trim($_GET['buscar'] ?? ''),
            'estado' => $_GET['estado'] ?? '',
        ];
        $pagina = $this->pagina();
        $usuarios = $this->modelo->listarUsuarios($filtros, $pagina, $this->porPagina);
        $totalUsuarios = $this->modelo->contarUsuarios($filtros);
        $totalPaginas = max(1, (int) ceil($totalUsuarios / $this->porPagina));
        $seccionActiva = 'usuarios';
        require __DIR__ . '/../views/admin/usuarios.php';
    }

    public function toggleEstado(): void {
        requireAdmin();
        $id = (int) ($_POST['idUsuario'] ?? 0);
        // No permitir desactivarse a uno mismo.
        if ($id === (int) $_SESSION['idUsuario']) {
            $this->volver('usuarios', 'no_self');
        }
        $ok = $this->modelo->toggleEstadoUsuario($id);
        $this->volver('usuarios', $ok ? 'estado_ok' : 'error');
    }

    public function cambiarEstadoUsuario(): void {
        requireAdmin();
        $id = (int) ($_POST['idUsuario'] ?? 0);
        $estado = $_POST['estado'] ?? '';
        // No cambiar tu propio estado (evita bloquearte a ti mismo).
        if ($id === (int) $_SESSION['idUsuario']) {
            $this->volver('usuarios', 'no_self');
        }
        $ok = $this->modelo->cambiarEstadoUsuario($id, $estado);
        $this->volver('usuarios', $ok ? 'estado_ok' : 'error');
    }

    public function cambiarRol(): void {
        requireAdmin();
        $id = (int) ($_POST['idUsuario'] ?? 0);
        $rol = $_POST['rol'] ?? '';
        // No cambiar tu propio rol (evita quitarte el admin por error).
        if ($id === (int) $_SESSION['idUsuario']) {
            $this->volver('usuarios', 'no_self');
        }
        $ok = $this->modelo->cambiarRolUsuario($id, $rol);
        $this->volver('usuarios', $ok ? 'rol_ok' : 'error');
    }

    public function eliminarUsuario(): void {
        requireAdmin();
        $id = (int) ($_POST['idUsuario'] ?? 0);
        // Protecciones: no borrarse a uno mismo, ni borrar a otro admin.
        if ($id === (int) $_SESSION['idUsuario']) {
            $this->volver('usuarios', 'no_self');
        }
        if ($this->modelo->esAdminUsuario($id)) {
            $this->volver('usuarios', 'no_admin');
        }
        $ok = $this->modelo->eliminarUsuarioAdmin($id);
        $this->volver('usuarios', $ok ? 'usuario_eliminado' : 'error');
    }

    /* ===================== REPORTES ===================== */
    public function reportes(): void {
        requireAdmin();
        $filtroEstado = $_GET['estado'] ?? '';
        $pagina = $this->pagina();
        $reportes = $this->modelo->listarReportes($filtroEstado, $pagina, $this->porPagina);
        $totalReportes = $this->modelo->contarReportes($filtroEstado);
        $totalPaginas = max(1, (int) ceil($totalReportes / $this->porPagina));
        $seccionActiva = 'reportes';
        require __DIR__ . '/../views/admin/reportes.php';
    }

    public function resolverReporte(): void {
        requireAdmin();
        $id = (int) ($_POST['idReporte'] ?? 0);
        $notas = trim($_POST['notas'] ?? '');
        $ok = $this->modelo->actualizarEstadoReporte($id, 'Revisado', $notas);
        $this->volver('reportes', $ok ? 'reporte_resuelto' : 'error');
    }

    public function descartarReporte(): void {
        requireAdmin();
        $id = (int) ($_POST['idReporte'] ?? 0);
        $notas = trim($_POST['notas'] ?? '');
        $ok = $this->modelo->actualizarEstadoReporte($id, 'Descartado', $notas);
        $this->volver('reportes', $ok ? 'reporte_descartado' : 'error');
    }

    public function eliminarAnuncioReportado(): void {
        requireAdmin();
        $idReporte = (int) ($_POST['idReporte'] ?? 0);
        $idAnuncio = $this->modelo->obtenerAnuncioDeReporte($idReporte);
        $ok = false;
        if ($idAnuncio) {
            // Borra el anuncio (esto también borra sus reportes) y deja constancia.
            $ok = $this->modelo->eliminarAnuncioAdmin($idAnuncio);
        }
        $this->volver('reportes', $ok ? 'anuncio_eliminado' : 'error');
    }

    /* ===================== ANUNCIOS ===================== */
    public function anuncios(): void {
        requireAdmin();
        $filtros = [
            'buscar' => trim($_GET['buscar'] ?? ''),
            'tipo'   => $_GET['tipo'] ?? '',
            'estado' => $_GET['estado'] ?? '',
        ];
        $pagina = $this->pagina();
        $anuncios = $this->modelo->listarTodosAnuncios($filtros, $pagina, $this->porPagina);
        $totalAnuncios = $this->modelo->contarTodosAnuncios($filtros);
        $totalPaginas = max(1, (int) ceil($totalAnuncios / $this->porPagina));
        $seccionActiva = 'anuncios';
        require __DIR__ . '/../views/admin/anuncios.php';
    }

    public function eliminarAnuncio(): void {
        requireAdmin();
        $id = (int) ($_POST['idAnuncio'] ?? 0);
        $ok = $this->modelo->eliminarAnuncioAdmin($id);
        $this->volver('anuncios', $ok ? 'anuncio_eliminado' : 'error');
    }

    public function cambiarEstadoAnuncio(): void {
        requireAdmin();
        $id = (int) ($_POST['idAnuncio'] ?? 0);
        $estado = $_POST['estado'] ?? '';
        $ok = $this->modelo->cambiarEstadoAnuncio($id, $estado);
        $this->volver('anuncios', $ok ? 'estado_ok' : 'error');
    }

    /* ===================== CATEGORÍAS ===================== */
    public function categorias(): void {
        requireAdmin();
        $categorias = $this->modelo->listarCategoriasAdmin();
        $seccionActiva = 'categorias';
        require __DIR__ . '/../views/admin/categorias.php';
    }

    public function crearCategoria(): void {
        requireAdmin();
        $nombre = trim($_POST['nombre'] ?? '');
        if ($nombre === '') {
            $this->volver('categorias', 'error');
        }
        $imagen = $this->subirImagenCategoria();
        $ok = $this->modelo->crearCategoria($nombre, $imagen);
        $this->volver('categorias', $ok ? 'categoria_creada' : 'error');
    }

    public function editarCategoria(): void {
        requireAdmin();
        $id = (int) ($_POST['idCategoria'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        if ($id <= 0 || $nombre === '') {
            $this->volver('categorias', 'error');
        }
        $imagen = $this->subirImagenCategoria(); // null si no subió nueva
        $ok = $this->modelo->editarCategoria($id, $nombre, $imagen);
        $this->volver('categorias', $ok ? 'categoria_editada' : 'error');
    }

    public function eliminarCategoria(): void {
        requireAdmin();
        $id = (int) ($_POST['idCategoria'] ?? 0);
        if ($this->modelo->contarAnunciosDeCategoria($id) > 0) {
            $this->volver('categorias', 'categoria_con_anuncios');
        }
        $ok = $this->modelo->eliminarCategoria($id);
        $this->volver('categorias', $ok ? 'categoria_eliminada' : 'error');
    }

    // Sube la imagen de categoría a assets/img/. Devuelve el nombre o null.
    private function subirImagenCategoria(): ?string {
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            return null;
        }
        if ($_FILES['imagen']['size'] > 2 * 1024 * 1024) {
            return null;
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['imagen']['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
            return null;
        }
        $nombre = 'cat_' . uniqid() . '.' . $ext;
        $destino = __DIR__ . '/../assets/img/' . $nombre;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            return $nombre;
        }
        return null;
    }

    private function volver(string $accion, string $estado): never {
        header('Location: ' . BASE_URL . 'controllers/AdminController.php?action=' . $accion . '&estado=' . urlencode($estado));
        exit();
    }
}

/* ===================== ROUTER ===================== */
// Toda accion del panel que modifique datos llega por POST: se valida el
// token CSRF antes de enrutar. Sin esto, una web externa podria hacer que
// un administrador con sesion abierta ascienda a otro usuario a admin.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdmin();
    verificarCsrf();
}

$controller = new AdminController();
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':               $controller->dashboard(); break;
    case 'usuarios':                $controller->usuarios(); break;
    case 'toggleEstado':            $controller->toggleEstado(); break;
    case 'cambiarEstadoUsuario':    $controller->cambiarEstadoUsuario(); break;
    case 'cambiarRol':              $controller->cambiarRol(); break;
    case 'eliminarUsuario':         $controller->eliminarUsuario(); break;
    case 'reportes':                $controller->reportes(); break;
    case 'resolverReporte':         $controller->resolverReporte(); break;
    case 'descartarReporte':        $controller->descartarReporte(); break;
    case 'eliminarAnuncioReportado':$controller->eliminarAnuncioReportado(); break;
    case 'anuncios':                $controller->anuncios(); break;
    case 'eliminarAnuncio':         $controller->eliminarAnuncio(); break;
    case 'cambiarEstadoAnuncio':    $controller->cambiarEstadoAnuncio(); break;
    case 'categorias':              $controller->categorias(); break;
    case 'crearCategoria':          $controller->crearCategoria(); break;
    case 'editarCategoria':         $controller->editarCategoria(); break;
    case 'eliminarCategoria':       $controller->eliminarCategoria(); break;
    default:                        $controller->dashboard(); break;
}
?>
