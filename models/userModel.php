<?php

    class UserModel{
        private $conn;

        public function __construct(){
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        // $passwordHash llega YA hasheado desde AuthController::registerFirst
        public function createUser($fotoPerfil, $nombres, $apellidos, $descripcionPerfil, $telefono, $correo, $passwordHash, $direccionDomicilio, $codigoPostal, $fechaRegistro, $estado, $idDistrito){
            $sql = "INSERT INTO usuario (fotoPerfil, nombres, apellidos, descripcionPerfil, telefono, correo, password, direccionDomicilio, codigoPostal, fechaRegistro, estado, idDistrito) VALUES (:fotoPerfil, :nombres, :apellidos, :descripcionPerfil, :telefono, :correo, :password, :direccionDomicilio, :codigoPostal, :fechaRegistro, :estado, :idDistrito)";
            $date = date('Y-m-d H:i:s');

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fotoPerfil', $fotoPerfil);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':descripcionPerfil', $descripcionPerfil);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':password', $passwordHash);
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

        // ===== "Recordarme" (cookie de sesión persistente) =====
        // Guarda el HASH del token (nunca el token en texto plano) junto a su fecha de expiración.
        public function guardarRememberToken($id, $tokenHash, $expira){
            $sql = "UPDATE usuario SET remember_token = :token, remember_token_expira = :expira WHERE idUsuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':token', $tokenHash);
            $stmt->bindParam(':expira', $expira);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        }

        // Busca un usuario activo por el hash del remember_token, solo si no expiró.
        public function getUserByRememberToken($tokenHash){
            $sql = "SELECT * FROM usuario WHERE remember_token = :token AND remember_token_expira > NOW()";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':token', $tokenHash);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Invalida el remember_token (logout, o rotación tras usarlo).
        public function limpiarRememberToken($id){
            $sql = "UPDATE usuario SET remember_token = NULL, remember_token_expira = NULL WHERE idUsuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
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
                // Guardamos el nombre de la foto ANTES de borrar la fila
                $stmtFoto = $this->conn->prepare("SELECT fotoPerfil FROM usuario WHERE idUsuario = ?");
                $stmtFoto->execute([$id]);
                $foto = $stmtFoto->fetchColumn();

                $this->conn->beginTransaction();

                $consultas = [
                    "DELETE FROM notificacion WHERE idUsuario = ?",
                    "DELETE FROM reporte WHERE idUsuarioReporta = ? OR idUsuarioReportado = ?",
                    "DELETE FROM reporte WHERE idAnuncio IN (SELECT idAnuncio FROM anuncio WHERE idUsuario = ?)",
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

                // Borra la foto de perfil del disco (después del commit, nunca la default)
                if ($foto) {
                    $foto = basename($foto);
                    if ($foto !== 'default.png') {
                        $ruta = __DIR__ . '/../assets/uploads/img_perfiles/' . $foto;
                        if (is_file($ruta)) { @unlink($ruta); }
                    }
                }
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

        // Resumen para el mini-dashboard del perfil:
        // total de anuncios, suma de vistas, postulaciones recibidas y calificación promedio.
        public function obtenerEstadisticasPerfil($idUsuario){
            try{
                $sql = "SELECT
                            (SELECT COUNT(*) FROM anuncio WHERE idUsuario = :id1) AS anuncios,
                            (SELECT COALESCE(SUM(vistas),0) FROM anuncio WHERE idUsuario = :id2) AS vistas,
                            (SELECT COUNT(*)
                                FROM postulacion p
                                INNER JOIN anuncio a ON p.idAnuncio = a.idAnuncio
                                WHERE a.idUsuario = :id3) AS postulaciones,
                            (SELECT COALESCE(ROUND(AVG(puntaje),1),0)
                                FROM calificacion
                                WHERE idUsuarioCalificado = :id4) AS calificacion";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':id1', $idUsuario, PDO::PARAM_INT);
                $stmt->bindValue(':id2', $idUsuario, PDO::PARAM_INT);
                $stmt->bindValue(':id3', $idUsuario, PDO::PARAM_INT);
                $stmt->bindValue(':id4', $idUsuario, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                error_log("Error al obtenerEstadisticasPerfil: " . $e->getMessage());
                return ['anuncios' => 0, 'vistas' => 0, 'postulaciones' => 0, 'calificacion' => 0];
            }
        }
    }
?>