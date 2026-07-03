<?php
require_once __DIR__ . '/../core/db/database.php';
require_once __DIR__ . '/userModel.php';

class AdminModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /* ============================================================
       DASHBOARD
       ============================================================ */
    public function obtenerEstadisticas(): array {
        try {
            $q = fn($sql) => (int) $this->conn->query($sql)->fetchColumn();

            $stats = [
                'total_usuarios'       => $q("SELECT COUNT(*) FROM usuario"),
                'usuarios_activos'     => $q("SELECT COUNT(*) FROM usuario WHERE estado = 'Activo'"),
                'usuarios_inactivos'   => $q("SELECT COUNT(*) FROM usuario WHERE estado <> 'Activo'"),
                'total_anuncios'       => $q("SELECT COUNT(*) FROM anuncio"),
                'anuncios_activos'     => $q("SELECT COUNT(*) FROM anuncio WHERE estado = 'Disponible'"),
                'anuncios_cancelados'  => $q("SELECT COUNT(*) FROM anuncio WHERE estado = 'Cancelado'"),
                'anuncios_finalizados' => $q("SELECT COUNT(*) FROM anuncio WHERE estado = 'Finalizado'"),
                'total_postulaciones'  => $q("SELECT COUNT(*) FROM postulacion"),
                'reportes_pendientes'  => $q("SELECT COUNT(*) FROM reporte WHERE estado = 'Pendiente'"),
                'total_categorias'     => $q("SELECT COUNT(*) FROM categoria"),
                'nuevos_usuarios_mes'  => $q("SELECT COUNT(*) FROM usuario WHERE YEAR(fechaRegistro) = YEAR(CURDATE()) AND MONTH(fechaRegistro) = MONTH(CURDATE())"),
            ];

            // Anuncios por mes (últimos 6 meses)
            $stmt = $this->conn->query("
                SELECT DATE_FORMAT(fechaPublicacion, '%Y-%m') AS mes, COUNT(*) AS total
                FROM anuncio
                WHERE fechaPublicacion >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY mes ORDER BY mes ASC
            ");
            $stats['anuncios_por_mes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Distribución trabajo vs servicio
            $stmt2 = $this->conn->query("SELECT tipoAnuncio, COUNT(*) AS total FROM anuncio GROUP BY tipoAnuncio");
            $stats['distribucion_tipo'] = $stmt2->fetchAll(PDO::FETCH_KEY_PAIR);

            return $stats;
        } catch (PDOException $e) {
            error_log("Admin obtenerEstadisticas: " . $e->getMessage());
            return [];
        }
    }

    /* ============================================================
       USUARIOS
       ============================================================ */
    public function listarUsuarios(array $filtros, int $pagina, int $porPagina): array {
        try {
            [$where, $params] = $this->whereUsuarios($filtros);
            $offset = ($pagina - 1) * $porPagina;
            $sql = "SELECT u.idUsuario, u.nombres, u.apellidos, u.correo, u.fotoPerfil,
                           u.estado, u.rol, u.fechaRegistro, d.nombre AS distrito
                    FROM usuario u
                    LEFT JOIN distrito d ON u.idDistrito = d.idDistrito
                    $where
                    ORDER BY u.idUsuario DESC
                    LIMIT $porPagina OFFSET $offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Admin listarUsuarios: " . $e->getMessage());
            return [];
        }
    }

    public function contarUsuarios(array $filtros): int {
        try {
            [$where, $params] = $this->whereUsuarios($filtros);
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuario u $where");
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    private function whereUsuarios(array $filtros): array {
        $cond = [];
        $params = [];
        if (!empty($filtros['buscar'])) {
            $cond[] = "(u.nombres LIKE ? OR u.apellidos LIKE ? OR u.correo LIKE ?)";
            $like = '%' . $filtros['buscar'] . '%';
            array_push($params, $like, $like, $like);
        }
        if (!empty($filtros['estado'])) {
            $cond[] = "u.estado = ?";
            $params[] = $filtros['estado'];
        }
        $where = $cond ? 'WHERE ' . implode(' AND ', $cond) : '';
        return [$where, $params];
    }

    public function toggleEstadoUsuario(int $idUsuario): bool {
        try {
            $stmt = $this->conn->prepare("
                UPDATE usuario
                SET estado = CASE WHEN estado = 'Activo' THEN 'Inactivo' ELSE 'Activo' END
                WHERE idUsuario = ?
            ");
            return $stmt->execute([$idUsuario]);
        } catch (PDOException $e) {
            error_log("Admin toggleEstadoUsuario: " . $e->getMessage());
            return false;
        }
    }

    // Asigna cualquiera de los 4 estados reales del ENUM.
    public function cambiarEstadoUsuario(int $idUsuario, string $estado): bool {
        if (!in_array($estado, ['Activo', 'Inactivo', 'Suspendido', 'Bloqueado'])) return false;
        try {
            $stmt = $this->conn->prepare("UPDATE usuario SET estado = ? WHERE idUsuario = ?");
            return $stmt->execute([$estado, $idUsuario]);
        } catch (PDOException $e) {
            error_log("Admin cambiarEstadoUsuario: " . $e->getMessage());
            return false;
        }
    }

    // Promueve a admin o quita el rol admin (vuelve a 'usuario').
    public function cambiarRolUsuario(int $idUsuario, string $rol): bool {
        if (!in_array($rol, ['usuario', 'admin'])) return false;
        try {
            $stmt = $this->conn->prepare("UPDATE usuario SET rol = ? WHERE idUsuario = ?");
            return $stmt->execute([$rol, $idUsuario]);
        } catch (PDOException $e) {
            error_log("Admin cambiarRolUsuario: " . $e->getMessage());
            return false;
        }
    }

    // Reutiliza la cascada segura ya existente en UserModel.
    public function eliminarUsuarioAdmin(int $idUsuario): bool {
        return (new UserModel())->eliminarCuentaCompleta($idUsuario);
    }

    public function esAdminUsuario(int $idUsuario): bool {
        try {
            $stmt = $this->conn->prepare("SELECT rol FROM usuario WHERE idUsuario = ?");
            $stmt->execute([$idUsuario]);
            return $stmt->fetchColumn() === 'admin';
        } catch (PDOException $e) {
            return false;
        }
    }

    /* ============================================================
       REPORTES
       ============================================================ */
    public function listarReportes(string $filtroEstado, int $pagina, int $porPagina): array {
        try {
            $cond = '';
            $params = [];
            if ($filtroEstado !== '' && in_array($filtroEstado, ['Pendiente', 'Revisado', 'Descartado'])) {
                $cond = "WHERE r.estado = ?";
                $params[] = $filtroEstado;
            }
            $offset = ($pagina - 1) * $porPagina;
            $sql = "SELECT r.idReporte, r.motivo, r.detalle, r.fecha, r.estado, r.fechaResolucion, r.notasAdmin,
                           r.idAnuncio, a.titulo AS anuncio_titulo,
                           ur.nombres AS reporta_nombres, ur.apellidos AS reporta_apellidos,
                           udo.nombres AS reportado_nombres, udo.apellidos AS reportado_apellidos
                    FROM reporte r
                    LEFT JOIN anuncio a ON r.idAnuncio = a.idAnuncio
                    LEFT JOIN usuario ur ON r.idUsuarioReporta = ur.idUsuario
                    LEFT JOIN usuario udo ON r.idUsuarioReportado = udo.idUsuario
                    $cond
                    ORDER BY (r.estado = 'Pendiente') DESC, r.fecha DESC
                    LIMIT $porPagina OFFSET $offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Admin listarReportes: " . $e->getMessage());
            return [];
        }
    }

    public function contarReportes(string $filtroEstado): int {
        try {
            $cond = '';
            $params = [];
            if ($filtroEstado !== '' && in_array($filtroEstado, ['Pendiente', 'Revisado', 'Descartado'])) {
                $cond = "WHERE estado = ?";
                $params[] = $filtroEstado;
            }
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM reporte $cond");
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    // Estado: 'Revisado' (resuelto) o 'Descartado'
    public function actualizarEstadoReporte(int $idReporte, string $estado, string $notas): bool {
        if (!in_array($estado, ['Revisado', 'Descartado'])) return false;
        try {
            $stmt = $this->conn->prepare("
                UPDATE reporte SET estado = ?, fechaResolucion = NOW(), notasAdmin = ?
                WHERE idReporte = ?
            ");
            return $stmt->execute([$estado, $notas, $idReporte]);
        } catch (PDOException $e) {
            error_log("Admin actualizarEstadoReporte: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerAnuncioDeReporte(int $idReporte): ?int {
        try {
            $stmt = $this->conn->prepare("SELECT idAnuncio FROM reporte WHERE idReporte = ?");
            $stmt->execute([$idReporte]);
            $r = $stmt->fetchColumn();
            return $r === false || $r === null ? null : (int) $r;
        } catch (PDOException $e) {
            return null;
        }
    }

    /* ============================================================
       ANUNCIOS
       ============================================================ */
    public function listarTodosAnuncios(array $filtros, int $pagina, int $porPagina): array {
        try {
            [$where, $params] = $this->whereAnuncios($filtros);
            $offset = ($pagina - 1) * $porPagina;
            $sql = "SELECT a.idAnuncio, a.titulo, a.tipoAnuncio, a.estado, a.fechaPublicacion, a.vistas,
                           u.nombres, u.apellidos
                    FROM anuncio a
                    INNER JOIN usuario u ON a.idUsuario = u.idUsuario
                    $where
                    ORDER BY a.idAnuncio DESC
                    LIMIT $porPagina OFFSET $offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Admin listarTodosAnuncios: " . $e->getMessage());
            return [];
        }
    }

    public function contarTodosAnuncios(array $filtros): int {
        try {
            [$where, $params] = $this->whereAnuncios($filtros);
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM anuncio a $where");
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    private function whereAnuncios(array $filtros): array {
        $cond = [];
        $params = [];
        if (!empty($filtros['buscar'])) {
            $cond[] = "a.titulo LIKE ?";
            $params[] = '%' . $filtros['buscar'] . '%';
        }
        if (!empty($filtros['tipo']) && in_array($filtros['tipo'], ['Trabajo', 'Servicio'])) {
            $cond[] = "a.tipoAnuncio = ?";
            $params[] = $filtros['tipo'];
        }
        if (!empty($filtros['estado']) && in_array($filtros['estado'], ['Disponible', 'En proceso', 'Finalizado', 'Cancelado'])) {
            $cond[] = "a.estado = ?";
            $params[] = $filtros['estado'];
        }
        $where = $cond ? 'WHERE ' . implode(' AND ', $cond) : '';
        return [$where, $params];
    }

    // Estados válidos reales del ENUM: Disponible, En proceso, Finalizado, Cancelado
    public function cambiarEstadoAnuncio(int $idAnuncio, string $nuevoEstado): bool {
        if (!in_array($nuevoEstado, ['Disponible', 'En proceso', 'Finalizado', 'Cancelado'])) return false;
        try {
            $stmt = $this->conn->prepare("UPDATE anuncio SET estado = ? WHERE idAnuncio = ?");
            return $stmt->execute([$nuevoEstado, $idAnuncio]);
        } catch (PDOException $e) {
            error_log("Admin cambiarEstadoAnuncio: " . $e->getMessage());
            return false;
        }
    }

    // Borra un anuncio y todo lo que depende de él, sin validar dueño (es admin).
    public function eliminarAnuncioAdmin(int $idAnuncio): bool {
        try {
            $this->conn->beginTransaction();
            $consultas = [
                "DELETE FROM categoriasanuncio WHERE idAnuncio = ?",
                "DELETE FROM postulacion WHERE idAnuncio = ?",
                "DELETE FROM anunciosfavoritos WHERE idAnuncio = ?",
                "DELETE FROM reporte WHERE idAnuncio = ?",
                "DELETE FROM anuncio WHERE idAnuncio = ?",
            ];
            foreach ($consultas as $sql) {
                $this->conn->prepare($sql)->execute([$idAnuncio]);
            }
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Admin eliminarAnuncioAdmin: " . $e->getMessage());
            return false;
        }
    }

    /* ============================================================
       CATEGORÍAS
       ============================================================ */
    public function listarCategoriasAdmin(): array {
        try {
            $stmt = $this->conn->query("
                SELECT c.idCategoria, c.nombre, c.imagen, COUNT(ca.idAnuncio) AS total_anuncios
                FROM categoria c
                LEFT JOIN categoriasanuncio ca ON c.idCategoria = ca.idCategoria
                GROUP BY c.idCategoria
                ORDER BY c.nombre ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Admin listarCategoriasAdmin: " . $e->getMessage());
            return [];
        }
    }

    public function crearCategoria(string $nombre, ?string $imagen): bool {
        try {
            $stmt = $this->conn->prepare("INSERT INTO categoria (nombre, imagen) VALUES (?, ?)");
            return $stmt->execute([$nombre, $imagen]);
        } catch (PDOException $e) {
            error_log("Admin crearCategoria: " . $e->getMessage());
            return false;
        }
    }

    public function editarCategoria(int $id, string $nombre, ?string $imagen): bool {
        try {
            if ($imagen !== null) {
                $stmt = $this->conn->prepare("UPDATE categoria SET nombre = ?, imagen = ? WHERE idCategoria = ?");
                return $stmt->execute([$nombre, $imagen, $id]);
            }
            $stmt = $this->conn->prepare("UPDATE categoria SET nombre = ? WHERE idCategoria = ?");
            return $stmt->execute([$nombre, $id]);
        } catch (PDOException $e) {
            error_log("Admin editarCategoria: " . $e->getMessage());
            return false;
        }
    }

    public function contarAnunciosDeCategoria(int $id): int {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM categoriasanuncio WHERE idCategoria = ?");
            $stmt->execute([$id]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    // Solo elimina si la categoría no tiene anuncios asociados.
    public function eliminarCategoria(int $id): bool {
        if ($this->contarAnunciosDeCategoria($id) > 0) {
            return false;
        }
        try {
            $stmt = $this->conn->prepare("DELETE FROM categoria WHERE idCategoria = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Admin eliminarCategoria: " . $e->getMessage());
            return false;
        }
    }
}
?>
