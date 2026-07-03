<?php
require_once __DIR__ . '/../core/db/database.php';
require_once __DIR__ . '/../core/config/session.php';
class PostulacionModel {
    private $conn;

    public function __construct() {
        $Database = new Database();
        $this->conn = $Database->getConnection();
    }

    public function obtenerPostulaciones($idUsuario) {
        try {
            $stmt = $this->conn->prepare("
                SELECT p.idPostulacion, p.estado, p.fecha, a.titulo AS puesto
                FROM postulacion p
                JOIN anuncio a ON p.idAnuncio = a.idAnuncio
                WHERE p.idUsuario = ?
                  AND (p.estado = 'Pendiente' OR p.fecha >= DATE_SUB(NOW(), INTERVAL 3 MONTH))
                ORDER BY p.fecha DESC
            ");
            $stmt->execute([$idUsuario]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al consultar postulaciones: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerUsuarioDePostulacion($idPostulacion) {
        try {
            $stmt = $this->conn->prepare("SELECT idUsuario FROM postulacion WHERE idPostulacion = ?");
            $stmt->execute([$idPostulacion]);
            $r = $stmt->fetchColumn();
            return $r === false ? null : (int) $r;
        } catch (Exception $e) {
            error_log("Error al obtener usuario de postulacion: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerEstadoAnuncio($idAnuncio): ?string {
        try {
            $stmt = $this->conn->prepare("SELECT estado FROM anuncio WHERE idAnuncio = ?");
            $stmt->execute([$idAnuncio]);
            $r = $stmt->fetchColumn();
            return $r === false ? null : (string) $r;
        } catch (Exception $e) {
            error_log("Error al obtener estado del anuncio: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerIdDuenioAnuncio($idAnuncio) {
        try {
            $stmt = $this->conn->prepare("SELECT idUsuario FROM anuncio WHERE idAnuncio = ?");
            $stmt->execute([$idAnuncio]);
            $r = $stmt->fetchColumn();
            return $r === false ? null : (int) $r;
        } catch (Exception $e) {
            error_log("Error al obtener dueño del anuncio: " . $e->getMessage());
            return null;
        }
    }

    public function yaPostulado($idUsuario, $idAnuncio): bool {
        try {
            $stmt = $this->conn->prepare("SELECT 1 FROM postulacion WHERE idUsuario = ? AND idAnuncio = ?");
            $stmt->execute([$idUsuario, $idAnuncio]);
            return (bool) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Error al verificar postulacion: " . $e->getMessage());
            return false;
        }
    }

    public function crearPostulacion($idUsuario, $idAnuncio): bool {
        try {
            $stmt = $this->conn->prepare("INSERT INTO postulacion (idUsuario, idAnuncio) VALUES (?, ?)");
            return $stmt->execute([$idUsuario, $idAnuncio]);
        } catch (Exception $e) {
            error_log("Error al crear postulacion: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPostulacionesRecibidas($idDueno) {
        try {
            $stmt = $this->conn->prepare("
                SELECT p.idPostulacion, p.estado, p.fecha,
                       a.idAnuncio, a.titulo AS puesto,
                       u.nombres, u.apellidos, u.telefono, u.correo, u.fotoPerfil
                FROM postulacion p
                JOIN anuncio a ON p.idAnuncio = a.idAnuncio
                JOIN usuario u ON p.idUsuario = u.idUsuario
                WHERE a.idUsuario = ?
                ORDER BY (p.estado = 'Pendiente') DESC, p.fecha DESC
            ");
            $stmt->execute([$idDueno]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al consultar postulaciones recibidas: " . $e->getMessage());
            return [];
        }
    }

    // Solo el dueño del anuncio puede cambiar el estado.
    public function actualizarEstado($idPostulacion, $estado, $idDueno): bool {
        try {
            $stmt = $this->conn->prepare("
                UPDATE postulacion
                SET estado = ?
                WHERE idPostulacion = ?
                  AND idAnuncio IN (SELECT idAnuncio FROM anuncio WHERE idUsuario = ?)
            ");
            $stmt->execute([$estado, $idPostulacion, $idDueno]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error al actualizar estado de postulacion: " . $e->getMessage());
            return false;
        }
    }
}
?>
