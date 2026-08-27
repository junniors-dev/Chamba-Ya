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

        /**
         * Cambia la contrasena y, en la misma operacion, invalida la cookie
         * "recordarme". Si alguien habia robado esa cookie, cambiar la
         * contrasena debe echarlo fuera; si no, el cambio no sirve de nada.
         */
        public function updatePassword($id, $newPassword){
            $sql = "UPDATE usuario SET password = :password, remember_token = NULL, remember_token_expira = NULL WHERE idUsuario = :id";
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
        // El vencimiento lo calcula MySQL, por el mismo motivo que en crearTokenReset:
        // PHP y la base de datos pueden ir en zonas horarias distintas.
        public function guardarRememberToken($id, $tokenHash, int $diasValidez = 30){
            $sql = "UPDATE usuario
                    SET remember_token = :token,
                        remember_token_expira = DATE_ADD(NOW(), INTERVAL :dias DAY)
                    WHERE idUsuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':token', $tokenHash);
            $stmt->bindValue(':dias', $diasValidez, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
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

        /* ====================================================================
           MODO ACTIVO (trabajador / cliente)
           No son roles excluyentes: el usuario conserva todas sus capacidades.
           El modo solo decide qué panel y qué navegación se le muestran, para
           que la interfaz no le mezcle "buscar chamba" con "buscar trabajador".
           ==================================================================== */
        public function cambiarModo($id, string $modo): bool {
            // Lista blanca: el valor viene de un formulario, así que nunca se
            // pasa directo a la consulta aunque la columna sea un ENUM.
            if (!in_array($modo, ['trabajador', 'cliente'], true)) {
                return false;
            }
            $stmt = $this->conn->prepare("UPDATE usuario SET modo = :modo WHERE idUsuario = :id");
            $stmt->bindValue(':modo', $modo);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function obtenerModo($id): string {
            try {
                $stmt = $this->conn->prepare("SELECT modo FROM usuario WHERE idUsuario = :id");
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $modo = $stmt->fetchColumn();
                return in_array($modo, ['trabajador', 'cliente'], true) ? $modo : 'trabajador';
            } catch (PDOException $e) {
                return 'trabajador';
            }
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

        /* ====================================================================
           RECUPERACIÓN DE CONTRASEÑA POR TOKEN DE UN SOLO USO
           Reemplaza al método anterior (correo + teléfono), que permitía tomar
           cualquier cuenta porque el teléfono se publica en los anuncios.
           ==================================================================== */

        /** Guarda el HASH del token. El token en claro solo viaja en el enlace. */
        /**
         * El vencimiento lo calcula MySQL con DATE_ADD(NOW(), ...) y no PHP.
         * Motivo: PHP y MySQL pueden estar en zonas horarias distintas (aquí había
         * 7 horas de diferencia). Si PHP escribe la fecha y MySQL la compara con su
         * propio NOW(), un enlace de 30 minutos acaba siendo válido 7 horas y media.
         * Usando el reloj de la base de datos en ambos lados, el plazo es exacto.
         */
        public function crearTokenReset($idUsuario, string $tokenHash, int $minutosValidez): bool {
            // Un usuario solo puede tener un enlace activo: se anulan los anteriores.
            $this->invalidarResetsDeUsuario($idUsuario);

            $sql = "INSERT INTO password_reset (idUsuario, token_hash, expira)
                    VALUES (:id, :token, DATE_ADD(NOW(), INTERVAL :minutos MINUTE))";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->bindValue(':token', $tokenHash);
            $stmt->bindValue(':minutos', $minutosValidez, PDO::PARAM_INT);
            return $stmt->execute();
        }

        /** Devuelve el reset solo si el token existe, no se usó y no caducó. */
        public function obtenerResetValido(string $tokenHash) {
            $sql = "SELECT r.idReset, r.idUsuario, u.correo, u.nombres
                    FROM password_reset r
                    INNER JOIN usuario u ON r.idUsuario = u.idUsuario
                    WHERE r.token_hash = :token
                      AND r.usado = 0
                      AND r.expira > NOW()";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':token', $tokenHash);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        /** Marca el token como consumido: un enlace de recuperación sirve una sola vez. */
        public function marcarResetUsado($idReset): bool {
            $stmt = $this->conn->prepare("UPDATE password_reset SET usado = 1 WHERE idReset = :id");
            $stmt->bindValue(':id', $idReset, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function invalidarResetsDeUsuario($idUsuario): bool {
            $stmt = $this->conn->prepare("UPDATE password_reset SET usado = 1 WHERE idUsuario = :id AND usado = 0");
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            return $stmt->execute();
        }

        /* ====================================================================
           CONTROL DE INTENTOS DE INICIO DE SESIÓN
           El contador vive en la base de datos y no en $_SESSION: si vive en la
           sesión, el atacante lo borra con su propia cookie y el bloqueo no sirve.
           ==================================================================== */

        public function registrarIntentoLogin(string $correo, string $ip, bool $exito): void {
            try {
                $stmt = $this->conn->prepare("INSERT INTO intento_login (correo, ip, exito) VALUES (:correo, :ip, :exito)");
                $stmt->bindValue(':correo', mb_substr($correo, 0, 80));
                $stmt->bindValue(':ip', mb_substr($ip, 0, 45));
                $stmt->bindValue(':exito', $exito ? 1 : 0, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error al registrar intento de login: " . $e->getMessage());
            }
        }

        /**
         * Cuenta los fallos recientes de ese correo O de esa IP.
         * Se miran ambos: por correo frena el ataque a una cuenta concreta, y por
         * IP frena a quien prueba muchos correos distintos desde el mismo sitio.
         */
        public function contarIntentosFallidos(string $correo, string $ip, int $ventanaSegundos): int {
            try {
                $sql = "SELECT COUNT(*) FROM intento_login
                        WHERE exito = 0
                          AND fecha > (NOW() - INTERVAL :ventana SECOND)
                          AND (correo = :correo OR ip = :ip)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':ventana', $ventanaSegundos, PDO::PARAM_INT);
                $stmt->bindValue(':correo', $correo);
                $stmt->bindValue(':ip', $ip);
                $stmt->execute();
                return (int) $stmt->fetchColumn();
            } catch (PDOException $e) {
                error_log("Error al contar intentos de login: " . $e->getMessage());
                return 0;
            }
        }

        /** Tras un login correcto se borran los fallos, para no arrastrar el bloqueo. */
        public function limpiarIntentosFallidos(string $correo, string $ip): void {
            try {
                $stmt = $this->conn->prepare("DELETE FROM intento_login WHERE exito = 0 AND (correo = :correo OR ip = :ip)");
                $stmt->bindValue(':correo', $correo);
                $stmt->bindValue(':ip', $ip);
                $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error al limpiar intentos de login: " . $e->getMessage());
            }
        }

        /** Borra los registros viejos para que la tabla no crezca sin control. */
        public function purgarIntentosAntiguos(int $dias = 7): void {
            try {
                $stmt = $this->conn->prepare("DELETE FROM intento_login WHERE fecha < (NOW() - INTERVAL :dias DAY)");
                $stmt->bindValue(':dias', $dias, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error al purgar intentos: " . $e->getMessage());
            }
        }
    }
?>