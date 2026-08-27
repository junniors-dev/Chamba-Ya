<?php
require_once __DIR__ . '/../models/AnuncioCreadoModel.php';
require_once __DIR__ . '/../core/config/session.php';
class AnuncioCreadoController {
    private AnuncioCreadoModel $modelo;

    public function __construct() {
        $this->modelo = new AnuncioCreadoModel();
    }

    public function obtenerAnuncios() {
        $idUsuario = obtenerIdUsuarioActivo();
        return $this->modelo->obtenerAnuncios($idUsuario);
    }

    public function obtenerCategorias() {
        return $this->modelo->obtenerCategorias();
    }

    public function procesarPeticion(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // Ruta 1: Eliminar anuncio
        if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
            $id = (int) ($_POST['id'] ?? 0);
            $this->eliminar($id);
            return;
        }

        // Ruta 2: Crear o Actualizar anuncio
        $datos = $this->capturarDatos();

        if (empty($datos['titulo']) || empty($datos['descripcion'])) {
            $this->redirigir('error_campos');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id === 0) {
            $this->guardar($datos);
        } else {
            $this->actualizar($id, $datos);
        }
    }

    private function capturarDatos(): array {
        // Mapa completo: cada estado del formulario corresponde a su estado real en BD
        // (antes 'Finalizado' se perdía: se convertía en 'Cancelado' al editar)
        $mapaEstado = [
            'activo'      => 'Disponible',
            'en_proceso'  => 'En proceso',
            'finalizado'  => 'Finalizado',
            'oculto'      => 'Cancelado',
        ];
        $estadoForm = $_POST['estado'] ?? 'activo';

        return [
            'titulo'               => trim($_POST['titulo'] ?? ''),
            'descripcion'          => trim($_POST['descripcion'] ?? ''),
            'estado'               => $mapaEstado[$estadoForm] ?? 'Disponible',
            'direccionEspecifica'  => trim($_POST['direccion_especifica'] ?? ''),
            'idDistrito'           => !empty($_POST['distrito_id']) ? (int)$_POST['distrito_id'] : null,
            'pago'                 => (float) ($_POST['pago'] ?? 0),
            'modalidad'            => $_POST['modalidad'] ?? 'Presencial',
            'tipo'                 => $_POST['tipo'] ?? 'Trabajo',
            'categorias_ids'       => !empty($_POST['categorias_ids'])
                                        ? explode(',', $_POST['categorias_ids'])
                                        : [],
        ];
    }

    private function guardar(array $datos): void {
        $idUsuario = obtenerIdUsuarioActivo();
        try {
            $idAnuncio = $this->modelo->guardarAnuncio(
                $datos['titulo'], $datos['descripcion'], $datos['estado'],
                $datos['direccionEspecifica'], $datos['idDistrito'], $datos['pago'], $datos['modalidad'], $datos['tipo'],
                $idUsuario
            );
            if ($idAnuncio) {
                $this->modelo->guardarCategoriasAnuncio($idAnuncio, $datos['categorias_ids']);
                $this->redirigir('guardado');
            }
        } catch (Exception $e) {
            error_log('[AnuncioCreadoController::guardar] ' . $e->getMessage());
            $this->redirigir('error_guardar');
        }
    }

    private function actualizar(int $id, array $datos): void {
        $idUsuario = obtenerIdUsuarioActivo();

        // Comprobación de propiedad: si el anuncio no es de este usuario, se corta.
        if (!$this->modelo->anuncioPerteneceAUsuario($id, $idUsuario)) {
            $this->redirigir('no_autorizado');
        }

        try {
            if ($this->modelo->actualizarAnuncio(
                $id, $datos['titulo'], $datos['descripcion'], $datos['estado'],
                $datos['direccionEspecifica'], $datos['idDistrito'], $datos['pago'], $datos['modalidad'], $datos['tipo'],
                $idUsuario
            )) {
                $this->modelo->guardarCategoriasAnuncio($id, $datos['categorias_ids']);
                $this->redirigir('actualizado');
            }
        } catch (Exception $e) {
            error_log('[AnuncioCreadoController::actualizar] ' . $e->getMessage());
            $this->redirigir('error_guardar');
        }
    }

    private function eliminar(int $id): void {
        $idUsuario = obtenerIdUsuarioActivo();

        // Comprobación de propiedad: si el anuncio no es de este usuario, se corta.
        if (!$this->modelo->anuncioPerteneceAUsuario($id, $idUsuario)) {
            $this->redirigir('no_autorizado');
        }

        try {
            $this->modelo->eliminarAnuncio($id, $idUsuario);
            $this->redirigir('eliminado');
        } catch (Exception $e) {
            error_log('[AnuncioCreadoController::eliminar] ' . $e->getMessage());
            $this->redirigir('error_eliminar');
        }
    }

    private function redirigir(string $mensaje): never {
        header("Location: ../views/user/mis_anuncios.php?mensaje=" . urlencode($mensaje));
        exit();
    }

    public function obtenerDepartamentos() {
        return $this->modelo->obtenerDepartamentos();
    }

    public function obtenerProvincias(int $idDepartamento) {
        return $this->modelo->obtenerProvinciasPorDepartamento($idDepartamento);
    }

    public function obtenerDistritos(int $idProvincia) {
        return $this->modelo->obtenerDistritosPorProvincia($idProvincia);
    }
}

// Pide estar logueado.
function exigirSesionAnuncios(): void {
    iniciarSesion();
    if (!isset($_SESSION['idUsuario'])) {
        header('Location: ../views/auth/login.php');
        exit();
    }
}

// AJAX: provincias y distritos.
if (isset($_GET['ajax'])) {
    exigirSesionAnuncios();
    header('Content-Type: application/json; charset=utf-8');
    $con = new AnuncioCreadoController();
    $action = $_GET['ajax'];

    if ($action === 'provincias' && isset($_GET['departamento_id'])) {
        echo json_encode($con->obtenerProvincias((int)$_GET['departamento_id']));
    } elseif ($action === 'distritos' && isset($_GET['provincia_id'])) {
        echo json_encode($con->obtenerDistritos((int)$_GET['provincia_id']));
    } else {
        echo json_encode([]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirSesionAnuncios();
    // Corta la peticion si no trae un token CSRF valido: impide que otra
    // web dispare esta accion aprovechando la sesion abierta del usuario.
    verificarCsrf();
    (new AnuncioCreadoController())->procesarPeticion();
}
?>
