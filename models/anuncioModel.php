<?php

    require_once __DIR__ . '/../core/db/database.php';

class AnuncioModel {
    private $conn;
    public function __construct() {
        require_once __DIR__ . '/../core/db/database.php';
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Obtiene las categorías ordenadas por como se agregaron (idCategoria)
    public function obtenerCategorias() {
        try {
            $stmt = $this->conn->query("SELECT idCategoria, nombre FROM categoria ORDER BY idCategoria ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerCategorias: " . $e->getMessage());
            return [];
        }
    }

    //correcion desde aqui
    public function obtenerDepartamentos() {
        try {
            $stmt = $this->conn->query("SELECT idDepartamento, nombre FROM departamento ORDER BY nombre ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerAnuncios($filtros = []) {
        try {
            // La ubicación del anuncio es un texto libre (a.ubicacion), por eso los
            // filtros geográficos se comparan contra el NOMBRE del lugar seleccionado.
            $sql = "SELECT
            a.*,
            u.nombres,
            u.apellidos,
            ROUND(AVG(cal.puntaje),1) AS promedio
            FROM anuncio a
            INNER JOIN usuario u ON a.idUsuario = u.idUsuario
            LEFT JOIN categoriasanuncio ca ON a.idAnuncio = ca.idAnuncio
            LEFT JOIN calificacion cal ON u.idUsuario = cal.idUsuarioCalificado

            WHERE 1=1";
            $params = [];
            
            //==================================
            // TIPO DE ANUNCIO
            //==================================
            if (!empty($filtros['tipoAnuncio'])) {

                $sql .= " AND a.tipoAnuncio = :tipoAnuncio ";

                $params[':tipoAnuncio'] = $filtros['tipoAnuncio'];
            }

            // BUSCADOR
            if (!empty($filtros['search'])) {
                $sql .= " AND (
                LOWER(a.titulo) LIKE LOWER(:search)
                OR LOWER(a.descripcion) LIKE LOWER(:search)
                )";
                $params[':search'] = '%' . trim($filtros['search']) . '%';
            }
            
            // CATEGORÍA
            if (!empty($filtros['idCategoria']) && is_array($filtros['idCategoria'])) {
                $placeholders = [];
                foreach ($filtros['idCategoria'] as $i => $idCategoria) {
                    $placeholder = ":categoria$i";
                    $placeholders[] = $placeholder;
                    $params[$placeholder] = (int)$idCategoria;
                }
                $sql .= " AND ca.idCategoria IN (" . implode(',', $placeholders) . ")";
            }
            
            // PRECIO MINIMO
            if (!empty($filtros['precioMin'])) {
                $sql .= " AND a.pagoReferencia >= :precioMin";
                $params[':precioMin'] = $filtros['precioMin'];
            }

            // DEPARTAMENTO: compara el texto de a.ubicacion con el nombre del departamento
            if (!empty($filtros['idDepartamento'])) {
                $sql .= " AND a.ubicacion LIKE CONCAT('%', (SELECT nombre FROM departamento WHERE idDepartamento = :idDepartamento), '%')";
                $params[':idDepartamento'] = (int)$filtros['idDepartamento'];
            }

            // PROVINCIA
            if (!empty($filtros['idProvincia'])) {
                $sql .= " AND a.ubicacion LIKE CONCAT('%', (SELECT nombre FROM provincia WHERE idProvincia = :idProvincia), '%')";
                $params[':idProvincia'] = (int)$filtros['idProvincia'];
            }

            // DISTRITO
            if (!empty($filtros['idDistrito'])) {
                $sql .= " AND a.ubicacion LIKE CONCAT('%', (SELECT nombre FROM distrito WHERE idDistrito = :idDistrito), '%')";
                $params[':idDistrito'] = (int)$filtros['idDistrito'];
            }
            
            //==================================
            // GROUP BY
            //==================================
            $sql .= "
                GROUP BY a.idAnuncio

                ORDER BY a.idAnuncio DESC
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage()); 
            return [];
        }
    }
    
    public function obtenerDetalleAnuncio($idAnuncio) {
    try {
        $sql = "SELECT a.*,
                       u.nombres, u.apellidos, u.telefono, u.correo, u.fotoPerfil, u.descripcionPerfil,
                       a.ubicacion AS ubicacion_completa,
                       GROUP_CONCAT(DISTINCT c.nombre SEPARATOR ', ') AS categorias_nombres
                FROM anuncio a
                INNER JOIN usuario u ON a.idUsuario = u.idUsuario
                LEFT JOIN categoriasanuncio ca ON a.idAnuncio = ca.idAnuncio
                LEFT JOIN categoria c ON ca.idCategoria = c.idCategoria
                WHERE a.idAnuncio = :idAnuncio
                GROUP BY a.idAnuncio";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':idAnuncio', $idAnuncio, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerDetalleAnuncio: " . $e->getMessage());
        return null;
    }
}

// NUEVO MÉTODO: Para listar los otros servicios que ofrece ese mismo usuario
public function obtenerAnunciosPorUsuario($idUsuario, $idAnuncioActual) {
    try {
        $sql = "SELECT a.*
                FROM anuncio a
                WHERE a.idUsuario = :idUsuario AND a.idAnuncio <> :idAnuncioActual AND a.tipoAnuncio = 'servicio'
                LIMIT 3";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':idAnuncioActual', $idAnuncioActual, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

    // La tabla calificacion no se relaciona con anuncio, sino con el usuario calificado.
    // Recibe el idUsuario dueño del anuncio y lista las opiniones que ha recibido.
    public function obtenerCalificacionesPorAnuncio($idUsuario) {
    try {
        $sql = "SELECT
                    c.puntaje,
                    c.comentario,
                    c.fecha AS fecha,
                    u.nombres,
                    u.apellidos,
                    u.fotoPerfil
                FROM calificacion c
                INNER JOIN usuario u ON c.idUsuarioCalificador = u.idUsuario
                WHERE c.idUsuarioCalificado = :idUsuario
                ORDER BY c.fecha DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerCalificacionesPorAnuncio: " . $e->getMessage());
        return [];
    }
}
}