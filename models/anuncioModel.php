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
            $sql = "SELECT DISTINCT
            a.*,
            u.nombres,
            u.apellidos,
            COALESCE(d.nombre,'No especificado') AS ubicacion,
            ROUND(AVG(cal.puntaje),1) AS promedio
            FROM anuncio a
            INNER JOIN usuario u ON a.idUsuario = u.idUsuario
            LEFT JOIN distrito d ON a.idDistrito = d.idDistrito
            LEFT JOIN provincia pr ON d.idProvincia = pr.idProvincia
            LEFT JOIN departamento dp ON pr.idDepartamento = dp.idDepartamento
            LEFT JOIN categoriasanuncio ca ON a.idAnuncio = ca.idAnuncio
            LEFT JOIN categoria c ON ca.idCategoria = c.idCategoria
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

            // DEPARTAMENTO
            if (!empty($filtros['idDepartamento'])) {
                $sql .= " AND dp.idDepartamento = :idDepartamento";
                $params[':idDepartamento'] = (int)$filtros['idDepartamento'];
            }

            // PROVINCIA
            if (!empty($filtros['idProvincia'])) {
                $sql .= " AND pr.idProvincia = :idProvincia";
                $params[':idProvincia'] = (int)$filtros['idProvincia'];
            }

            // DISTRITO
            if (!empty($filtros['idDistrito'])) {
                $sql .= " AND d.idDistrito = :idDistrito";
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
                       CONCAT(dep.nombre, ' - ', pr.nombre, ' - ', d.nombre) AS ubicacion_completa,
                       GROUP_CONCAT(DISTINCT c.nombre SEPARATOR ', ') AS categorias_nombres
                FROM anuncio a
                INNER JOIN usuario u ON a.idUsuario = u.idUsuario
                LEFT JOIN distrito d ON a.idDistrito = d.idDistrito
                LEFT JOIN provincia pr ON d.idProvincia = pr.idProvincia
                LEFT JOIN departamento dep ON pr.idDepartamento = dep.idDepartamento
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
        $sql = "SELECT a.*, d.nombre AS nombre_distrito 
                FROM anuncio a
                LEFT JOIN distrito d ON a.idDistrito = d.idDistrito
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

    public function obtenerCalificacionesPorAnuncio($idAnuncio) {
    try {
        // Ajusta los nombres de las columnas 'puntaje', 'comentario', 'fecha' si varían en tu BD
        $sql = "SELECT 
                    c.puntaje, 
                    c.comentario, 
                    c.fechaCalificacion AS fecha, 
                    u.nombres, 
                    u.apellidos,
                    u.fotoPerfil
                FROM calificacion c
                INNER JOIN usuario u ON c.idUsuario = u.idUsuario
                WHERE c.idAnuncio = :idAnuncio
                ORDER BY c.fechaCalificacion DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idAnuncio', $idAnuncio, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerCalificacionesPorAnuncio: " . $e->getMessage());
        return [];
    }
}
}