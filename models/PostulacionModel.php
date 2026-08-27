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

    /**
     * Cierra un trabajo: solo el dueno del anuncio puede darlo por completado, y
     * solo si antes lo habia aceptado. Sin esa condicion se podria saltar el
     * flujo y marcar como completada una postulacion recien llegada.
     */
    public function marcarCompletada($idPostulacion, $idDueno): bool {
        try {
            $stmt = $this->conn->prepare("
                UPDATE postulacion
                SET estado = 'Completado', fechaCompletado = NOW()
                WHERE idPostulacion = ?
                  AND estado = 'Aceptado'
                  AND idAnuncio IN (SELECT idAnuncio FROM anuncio WHERE idUsuario = ?)
            ");
            $stmt->execute([$idPostulacion, $idDueno]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error al marcar postulacion completada: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Devuelve la postulacion completada que une a dos personas, o null.
     *
     * Es la puerta que habilita las calificaciones: antes cualquier usuario podia
     * calificar a cualquier otro sin haber trabajado nunca con el, lo que dejaba
     * la puerta abierta a resenas falsas. Se acepta en los dos sentidos porque
     * ambas partes pueden calificarse: quien publico el anuncio y quien lo hizo.
     */
    public function trabajoCompletadoEntre(int $idA, int $idB): ?array {
        try {
            $sql = "SELECT p.idPostulacion, p.idAnuncio
                    FROM postulacion p
                    INNER JOIN anuncio a ON p.idAnuncio = a.idAnuncio
                    WHERE p.estado = 'Completado'
                      AND (
                            (a.idUsuario = :a1 AND p.idUsuario = :b1)
                         OR (a.idUsuario = :b2 AND p.idUsuario = :a2)
                          )
                    ORDER BY p.fechaCompletado DESC
                    LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':a1', $idA, PDO::PARAM_INT);
            $stmt->bindValue(':a2', $idA, PDO::PARAM_INT);
            $stmt->bindValue(':b1', $idB, PDO::PARAM_INT);
            $stmt->bindValue(':b2', $idB, PDO::PARAM_INT);
            $stmt->execute();
            $r = $stmt->fetch(PDO::FETCH_ASSOC);
            return $r ?: null;
        } catch (Exception $e) {
            error_log("Error en trabajoCompletadoEntre: " . $e->getMessage());
            return null;
        }
    }
}
?>
