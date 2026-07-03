<?php

    class UserModel{
        private $conn;

        public function __construct(){
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function createUser($fotoPerfil, $nombres, $apellidos, $descripcionPerfil, $telefono, $correo, $password, $direccionDomicilio, $codigoPostal, $fechaRegistro, $estado, $idDistrito){
            $sql = "INSERT INTO usuario (fotoPerfil, nombres, apellidos, descripcionPerfil, telefono, correo, password, direccionDomicilio, codigoPostal, fechaRegistro, estado, idDistrito) VALUES (:fotoPerfil, :nombres, :apellidos, :descripcionPerfil, :telefono, :correo, :password, :direccionDomicilio, :codigoPostal, :fechaRegistro, :estado, :idDistrito)";
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $date = date('Y-m-d H:i:s');

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fotoPerfil', $fotoPerfil);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':descripcionPerfil', $descripcionPerfil);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':direccionDomicilio', $direccionDomicilio);
            $stmt->bindParam(':codigoPostal', $codigoPostal);
            $stmt->bindParam(':fechaRegistro', $date);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':idDistrito', $idDistrito);

            return $stmt->execute();
        }

        public function getUserByEmail($correo){
            $sql = "SELECT * FROM usuario WHERE correo = :correo";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function updatePassword($id, $newPassword){
            $sql = "UPDATE usuario SET password = :password WHERE idUsuario = :id";
            $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':password', $password_hash);
            return $stmt->execute();
        }

        public function getUserById($id){
            $sql = "SELECT * FROM usuario WHERE idUsuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function desactivarUsuario($id){
            $sql = "UPDATE usuario SET estado = 'Inactivo' WHERE idUsuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        }

        public function reactivarUsuario($id){
            $sql = "UPDATE usuario SET estado = 'Activo' WHERE idUsuario = :id AND estado = 'Inactivo'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        }

        // Borra al usuario y todo lo que depende de él, en una transacción.
        public function eliminarCuentaCompleta($id){
            try {
                $this->conn->beginTransaction();

                $consultas = [
                    "DELETE FROM postulacion WHERE idUsuario = ?",
                    "DELETE FROM postulacion WHERE idAnuncio IN (SELECT idAnuncio FROM anuncio WHERE idUsuario = ?)",
                    "DELETE FROM anunciosfavoritos WHERE idUsuario = ?",
                    "DELETE FROM anunciosfavoritos WHERE idAnuncio IN (SELECT idAnuncio FROM anuncio WHERE idUsuario = ?)",
                    "DELETE FROM calificacion WHERE idUsuarioCalificado = ? OR idUsuarioCalificador = ?",
                    "DELETE FROM trabajadoresfavoritos WHERE idUsuarioCliente = ? OR idUsuarioTrabajador = ?",
                    "DELETE FROM usuariohabilidad WHERE idUsuario = ?",
                    "DELETE FROM categoriasanuncio WHERE idAnuncio IN (SELECT idAnuncio FROM anuncio WHERE idUsuario = ?)",
                    "DELETE FROM anuncio WHERE idUsuario = ?",
                    "DELETE FROM usuario WHERE idUsuario = ?",
                ];
                foreach ($consultas as $sql) {
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute(array_fill(0, substr_count($sql, '?'), $id));
                }

                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollBack();
                error_log("Error al eliminar cuenta: " . $e->getMessage());
                return false;
            }
        }

        public function emailExists($correo){
            $sql = "SELECT COUNT(*) FROM usuario WHERE correo = :correo";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        }

        // El correo ya lo usa OTRO usuario distinto (para validar al editar perfil).
        public function correoEnUsoPorOtro($correo, $idUsuario){
            $sql = "SELECT COUNT(*) FROM usuario WHERE correo = :correo AND idUsuario <> :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':id', $idUsuario);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        }

        public function getPreferencias($id){
            $sql = "SELECT notif_ofertas, notif_vistas, notif_boletin, visibilidad FROM usuario WHERE idUsuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function guardarPreferencias($id, $ofertas, $vistas, $boletin, $visibilidad){
            $sql = "UPDATE usuario SET notif_ofertas = :o, notif_vistas = :v, notif_boletin = :b, visibilidad = :vis WHERE idUsuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':o', $ofertas, PDO::PARAM_INT);
            $stmt->bindValue(':v', $vistas, PDO::PARAM_INT);
            $stmt->bindValue(':b', $boletin, PDO::PARAM_INT);
            $stmt->bindValue(':vis', $visibilidad);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        }

        public function getDepartamentos(){
            $sql = "SELECT idDepartamento, nombre FROM departamento";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        //IMPORTANTE: devuelve los IDs (idDistrito, idProvincia, idDepartamento) además de los nombres
        public function getFullLocationByIdDistrito($idDistrito){
            $sql = "SELECT d.idDistrito, d.nombre as distrito,
                        p.idProvincia, p.nombre as provincia,
                        dep.idDepartamento, dep.nombre as departamento
                    FROM distrito d 
                    INNER JOIN provincia p ON d.idProvincia = p.idProvincia 
                    INNER JOIN departamento dep ON p.idDepartamento = dep.idDepartamento 
                    WHERE d.idDistrito = :idDistrito";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':idDistrito', $idDistrito);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function updateUserProfileData($id, $nombres, $apellidos, $correo, $telefono, $direccionDomicilio, $codigoPostal, $idDistrito = null, $fotoPerfil = null){
            // Si se sube una foto nueva, guardamos la anterior para borrarla del disco luego
            $fotoAnterior = null;
            if($fotoPerfil){
                $stmtFoto = $this->conn->prepare("SELECT fotoPerfil FROM usuario WHERE idUsuario = :id");
                $stmtFoto->bindParam(':id', $id);
                $stmtFoto->execute();
                $fotoAnterior = $stmtFoto->fetchColumn();

                $sql = "UPDATE usuario SET nombres = :nombres, apellidos = :apellidos, correo = :correo, telefono = :telefono, direccionDomicilio = :direccionDomicilio, codigoPostal = :codigoPostal, idDistrito = :idDistrito, fotoPerfil = :fotoPerfil WHERE idUsuario = :id";
            } else {
                $sql = "UPDATE usuario SET nombres = :nombres, apellidos = :apellidos, correo = :correo, telefono = :telefono, direccionDomicilio = :direccionDomicilio, codigoPostal = :codigoPostal, idDistrito = :idDistrito WHERE idUsuario = :id";
            }
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccionDomicilio', $direccionDomicilio);
            $stmt->bindParam(':codigoPostal', $codigoPostal);
            $stmt->bindParam(':idDistrito', $idDistrito);

            if($fotoPerfil){
                $stmt->bindParam(':fotoPerfil', $fotoPerfil);
            }

            $resultado = $stmt->execute();

            // Elimina la foto anterior del filesystem (solo si el update fue exitoso,
            // hay foto nueva, y la anterior no es la default ni la misma).
            if($resultado && $fotoPerfil && $fotoAnterior){
                $fotoAnterior = basename($fotoAnterior); // evita path traversal
                if($fotoAnterior !== 'default.png' && $fotoAnterior !== basename($fotoPerfil)){
                    $ruta = __DIR__ . '/../assets/uploads/img_perfiles/' . $fotoAnterior;
                    if(is_file($ruta)){
                        @unlink($ruta);
                    }
                }
            }

            return $resultado;
        }
        public function obtenerCalificacionUsuario($idUsuario){
            try{
                $sql = "SELECT ROUND(AVG(puntaje),0) AS puntaje
                        FROM calificacion
                        WHERE idUsuarioCalificado = :idUsuario";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                error_log("Error al obtenerCalificacionDelUsuario: " . $e->getMessage());
                return ['puntaje' => 0];
            }
        }
    }
?>