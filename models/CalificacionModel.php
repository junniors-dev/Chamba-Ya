<?php
require_once __DIR__ . '/../core/db/database.php';
require_once __DIR__ . '/../core/config/session.php';

class CalificacionModel {
    private $conn;

    public function __construct() {
        $Database = new Database();
        $this->conn = $Database->getConnection();
    }

    public function usuarioExiste($idUsuario): bool {
        try {
            $stmt = $this->conn->prepare("SELECT 1 FROM usuario WHERE idUsuario = ?");
            $stmt->execute([$idUsuario]);
            return (bool) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Error al verificar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function yaCalifico($idCalificador, $idCalificado): bool {
        try {
            $stmt = $this->conn->prepare(
                "SELECT 1 FROM calificacion WHERE idUsuarioCalificador = ? AND idUsuarioCalificado = ?"
            );
            $stmt->execute([$idCalificador, $idCalificado]);
            return (bool) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Error al verificar calificacion: " . $e->getMessage());
            return false;
        }
    }

    // Se guarda la postulación completada que justifica la calificación: así queda
    // trazable de qué trabajo salió cada reseña y no se pueden inventar.
    public function crear($idCalificador, $idCalificado, $puntaje, $comentario, ?int $idPostulacion = null): bool {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO calificacion (idUsuarioCalificador, idUsuarioCalificado, puntaje, comentario, idPostulacion)
                 VALUES (?, ?, ?, ?, ?)"
            );
            return $stmt->execute([$idCalificador, $idCalificado, $puntaje, $comentario, $idPostulacion]);
        } catch (Exception $e) {
            error_log("Error al crear calificacion: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($idCalificador, $idCalificado, $puntaje, $comentario): bool {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE calificacion SET puntaje = ?, comentario = ?, fecha = NOW()
                 WHERE idUsuarioCalificador = ? AND idUsuarioCalificado = ?"
            );
            return $stmt->execute([$puntaje, $comentario, $idCalificador, $idCalificado]);
        } catch (Exception $e) {
            error_log("Error al actualizar calificacion: " . $e->getMessage());
            return false;
        }
    }
}
?>
