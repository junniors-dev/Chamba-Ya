<?php
/**
 * Portafolio de trabajos anteriores de un usuario.
 *
 * Completa el perfil del trabajador: la descripción y las habilidades ya
 * existían, pero no había forma de enseñar trabajos ya realizados.
 */
class PortafolioModel {
    private $conn;

    // Un perfil no puede tener más de estos trabajos: evita que alguien llene
    // el disco subiendo imágenes sin límite.
    const MAX_POR_USUARIO = 12;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /** Trabajos de un usuario, del más reciente al más antiguo. */
    public function obtenerDeUsuario($idUsuario): array {
        try {
            $sql = "SELECT p.idPortafolio, p.titulo, p.descripcion, p.imagen, p.fecha,
                           c.nombre AS categoria
                    FROM portafolio p
                    LEFT JOIN categoria c ON p.idCategoria = c.idCategoria
                    WHERE p.idUsuario = :id
                    ORDER BY p.fecha DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en portafolio obtenerDeUsuario: " . $e->getMessage());
            return [];
        }
    }

    public function contarDeUsuario($idUsuario): int {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM portafolio WHERE idUsuario = :id");
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function crear($idUsuario, string $titulo, string $descripcion, ?string $imagen, ?int $idCategoria): bool {
        try {
            $sql = "INSERT INTO portafolio (idUsuario, titulo, descripcion, imagen, idCategoria)
                    VALUES (:id, :titulo, :descripcion, :imagen, :categoria)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':descripcion', $descripcion);
            $stmt->bindValue(':imagen', $imagen);
            $stmt->bindValue(':categoria', $idCategoria, $idCategoria === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en portafolio crear: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Comprobación de propiedad: sin esto, cualquiera podría borrar el trabajo
     * de otro cambiando el id en el formulario.
     */
    public function perteneceAUsuario(int $idPortafolio, int $idUsuario): bool {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM portafolio WHERE idPortafolio = :idp AND idUsuario = :idu");
            $stmt->bindValue(':idp', $idPortafolio, PDO::PARAM_INT);
            $stmt->bindValue(':idu', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /** Devuelve el nombre de la imagen para poder borrarla del disco. */
    public function obtenerImagen(int $idPortafolio): ?string {
        try {
            $stmt = $this->conn->prepare("SELECT imagen FROM portafolio WHERE idPortafolio = :id");
            $stmt->bindValue(':id', $idPortafolio, PDO::PARAM_INT);
            $stmt->execute();
            $img = $stmt->fetchColumn();
            return $img ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function eliminar(int $idPortafolio, int $idUsuario): bool {
        try {
            // El idUsuario va en el WHERE además de comprobarse antes: doble
            // barrera para que un id ajeno no pueda borrarse nunca.
            $stmt = $this->conn->prepare("DELETE FROM portafolio WHERE idPortafolio = :idp AND idUsuario = :idu");
            $stmt->bindValue(':idp', $idPortafolio, PDO::PARAM_INT);
            $stmt->bindValue(':idu', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error en portafolio eliminar: " . $e->getMessage());
            return false;
        }
    }
}
